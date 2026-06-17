<?php

namespace The42dx\Whatsapp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use The42dx\Whatsapp\Enums\{ContextType, MessageType, MessageWay};
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Models\WhatsappMessage;

/**
 * Class WhatsappService
 *
 * Service for sending messages via WhatsApp Business API.
 */
class WhatsappService {
    /**
     * @var Client HTTP client for making requests to WhatsApp API
     */
    private Client $http;

    /**
     * @var string API version for WhatsApp Business API
     */
    private string $apiVersion;

    /**
     * @var string WhatsApp Business ID for Business API
     */
    private string $businessId;

    /**
     * @var string Business phone ID for WhatsApp Business API
     */
    private string $businessPhoneId;

    /**
     * @var string Server URL for WhatsApp Business API
     */
    private string $serverUrl;

    /**
     * @var string Token for WhatsApp Business API
     */
    private string $token;

    /**
     * @var string Business phone number for WhatsApp Business API
     */
    private string $businessPhoneNumber;

    /**
     * WhatsappService constructor.
     *
     * Created the WhatsApp service instance. Generate the service
     * with the necessary configuration and HTTP client if none is provided.
     *
     * @param  Client|null  $http  Optional HTTP client for making requests to WhatsApp API
     *
     * @throws InvalidArgumentException if configuration is missing or invalid
     */
    public function __construct(?Client $http = null) {
        if (empty(config('whatsapp.server_url')) || empty(config('whatsapp.api_version')) || empty(config('whatsapp.business_phone_id')) || empty(config('whatsapp.token'))) {
            Log::error('WhatsApp configuration is missing or invalid. Please check your configuration settings.');

            throw new InvalidArgumentException('WhatsApp configuration is missing or invalid.');
        }

        $this->serverUrl = config('whatsapp.server_url');
        $this->apiVersion = config('whatsapp.api_version');
        $this->businessPhoneId = config('whatsapp.business_phone_id');
        $this->businessId = config('whatsapp.business_id');
        $this->businessPhoneNumber = config('whatsapp.business_phone_number');
        $this->token = config('whatsapp.token');
        $this->http = $http ?? new Client([
            'base_uri' => "{$this->serverUrl}/{$this->apiVersion}/",
            'headers' => ['Authorization' => "Bearer {$this->token}"],
        ]);
    }

    /**
     * send
     *
     * Sends a message via WhatsApp Business API.
     *
     * @param  WhatsappApiMessage  $wppApiMsg  The Whatsapp API message that will be sent
     * @param  ?Model  $messageable  Optional messageable model (to build message relationship)
     * @return array The response body from the WhatsApp API
     */
    public function send(WhatsappApiMessage $wppApiMsg, ?Model $messageable = null): array {
        $responseBody = [];

        try {
            $response = $this->http->post("{$this->businessPhoneId}/messages", ['json' => $wppApiMsg->toArray()]);
            $responseBody = json_decode($response->getBody()->getContents(), true);

            Log::info('Message sent to WhatsApp API', ['apiResponse' => $responseBody]);

            if ($wppApiMsg->type === MessageType::REACTION) {
                $this->updateMessageRecordWithReaction($wppApiMsg);
            } else {
                $this->createMessageRecord($responseBody, $wppApiMsg, $messageable);
            }
        } catch (RequestException $th) {
            Log::error('Error sending whatsapp message', [
                'statusCode' => $th->getResponse()->getStatusCode(),
                'body' => $th->getResponse()->getBody()->getContents(),
                'error' => $th->getMessage(),
            ]);
        }

        return $responseBody;
    }

    /**
     * getMessageTemplates
     *
     * Retrieves the available message templates from the WhatsApp API.
     *
     * @return array The list of message templates
     */
    public function getMessageTemplates(): array {
        $body = [];

        try {
            $response = $this->http->get("{$this->businessId}/message_templates");
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $th) {
            Log::error('Error getting available templates', [
                'statusCode' => $th->getResponse()->getStatusCode(),
                'body' => $th->getResponse()->getBody()->getContents(),
                'error' => $th->getMessage(),
            ]);
        }

        return $body;
    }

    /**
     * createMessageRecord
     *
     * Creates a record of the sent WhatsApp message in the database.
     *
     * @param  array  $body  The response body from the WhatsApp API
     * @param  WhatsappApiMessage  $apiMessage  The original API message payload
     * @param  Model  $messageable  The messageable model containing the recipient's phone number
     */
    private function createMessageRecord(array $body, WhatsappApiMessage $apiMessage, ?Model $messageable = null): void {
        if (isset($body['messages']) && isset($body['messages'][0]) && isset($body['messages'][0]['id'])) {
            $templatePayload = $apiMessage->type === MessageType::TEMPLATE && isset($apiMessage->template) ? [[...$apiMessage->template, 'type' => MessageType::TEMPLATE->value]] : [];

            $contextPayload = isset($apiMessage->context)
                ? [[
                    'type' => isset($apiMessage->context['message_id']) ? ContextType::REPLY : null,
                    'context' => $apiMessage->context['message_id'] ?? null,
                ]] : [];

            $record = WhatsappMessage::create([
                'text' => isset($apiMessage->text) && isset($apiMessage->text['body'])
                    ? $apiMessage->text['body']
                    : null,
                'contact_phone_number' => $apiMessage->to,
                (config('whatsapp.database.messageable_id_column')) => $messageable
                    ? $messageable->id
                    : null,
                'type' => $apiMessage->type,
                'whatsapp_message_id' => $body['messages'][0]['id'],
                'way' => MessageWay::OUTBOUND,
                'payload' => array_merge($contextPayload, $templatePayload),
            ]);

            Log::info('Message record created', Arr::only($record->toArray(), ['id', 'whatsapp_message_id']));
        }
    }

    /**
     * updateMessageRecordWithReaction
     *
     * Updates the message record in the database with the reaction information.
     *
     * @param  WhatsappApiMessage  $apiMessage  The original API message payload containing the reaction data
     */
    private function updateMessageRecordWithReaction(WhatsappApiMessage $apiMessage): void {
        $reactedToMsg = WhatsappMessage::where('whatsapp_message_id', $apiMessage->reaction['message_id'])->first();

        if (!$reactedToMsg) {
            Log::warning('Message not found on the database: ' . $apiMessage->reaction['message_id']);

            return;
        }

        $reactionsModelPayload = array_filter($reactedToMsg->getPayloadType(MessageType::REACTION), fn($reaction) => $reaction['from'] !== $this->businessPhoneNumber);
        $noReactionsModelPayload = $reactedToMsg->getPayloadWithoutType(MessageType::REACTION);

        if (isset($apiMessage->reaction['emoji']) && !empty($apiMessage->reaction['emoji'])) {
            $reactionsModelPayload[] = [
                'type' => MessageType::REACTION->value,
                'emoji' => $apiMessage->reaction['emoji'],
                'from' => $this->businessPhoneNumber,
            ];
        }

        $reactedToMsg->update(['payload' => array_merge($noReactionsModelPayload, $reactionsModelPayload)]);
    }
}
