<?php

namespace The42dx\Whatsapp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use The42dx\Whatsapp\Enums\{ContextType, MessageType, MessageWay};
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
     * @param  MessageType  $type  The type of message to send
     * @param  Model  $messageable  The messageable model containing the recipient's phone number
     * @param  array|string  $data  The message content or data
     * @param  WhatsappMessage  $replyTo  The message to which this message is a reply
     * @return array The response body from the WhatsApp API
     */
    public function send(MessageType $type, Model $messageable, array|string $data, ?WhatsappMessage $replyTo = null): array {
        $body = [];
        $to = $messageable->{config('whatsapp.database.messageable_phone_column')};

        if (is_null($to)) {
            Log::warning('User does not have a phone number set', [(config('whatsapp.database.messageable_id_column')) => $messageable->id]);

            return $body;
        }

        $apiMessage = $this->getApiMessage($type, $to, $data, $replyTo);

        if (!$apiMessage) {
            return $body;
        }

        try {
            $response = $this->http->post("{$this->businessPhoneId}/messages", ['json' => $apiMessage]);
            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('Message sent to WhatsApp API', ['response' => $body]);

            if ($apiMessage['type'] === MessageType::REACTION->value) {
                $this->updateMessageRecordWithReaction($apiMessage);
            } else {
                $this->createMessageRecord($body, $messageable, $type, $apiMessage);
            }
        } catch (RequestException $th) {
            Log::error('Error sending whatsapp message', [
                'body' => $th->getResponse()->getBody()->getContents(),
                'error' => $th->getMessage(),
            ]);
        }

        return $body;
    }

    /**
     * getApiMessage
     *
     * Generates the API message payload based on the message type.
     *
     * @param  MessageType  $type  The type of message to send
     * @param  string  $whatsappPhone  The recipient's WhatsApp phone number
     * @param  array|string  $data  The message content or data
     * @param  WhatsappMessage  $replyTo  The message to which this message is a reply
     * @return array|null The API message payload or null if unsupported type
     */
    private function getApiMessage(MessageType $type, string $whatsappPhone, array|string $data, ?WhatsappMessage $replyTo): ?array {
        $apiMsg = $this->setMesgCtx($whatsappPhone, $replyTo);

        switch ($type) {
            case MessageType::TEXT:
                return $this->createTextMsg($apiMsg, is_array($data) ? $data['text'] : $data);
            case MessageType::REACTION:
                return $this->createReactionMsg($apiMsg, $data);
            case MessageType::AUDIO:
            case MessageType::BUTTON:
            case MessageType::CONTACTS:
            case MessageType::DOCUMENT:
            case MessageType::IMAGE:
            case MessageType::INTERACTIVE:
            case MessageType::LOCATION:
            case MessageType::STICKER:
            case MessageType::TEMPLATE:
            case MessageType::VIDEO:
            default:
                Log::warning('Unsupported message type: ' . $type->value);
                Log::debug('Unsupported message content:', ['content' => $data]);

                return null;
        }
    }

    /**
     * createMessageRecord
     *
     * Creates a record of the sent WhatsApp message in the database.
     *
     * @param  array  $body  The response body from the WhatsApp API
     * @param  Model  $messageable  The messageable model containing the recipient's phone number
     * @param  MessageType  $type  The type of message sent
     * @param  array  $apiMessage  The original API message payload
     */
    private function createMessageRecord(array $body, Model $messageable, MessageType $type, array $apiMessage): void {
        if (isset($body['messages']) && isset($body['messages'][0]) && isset($body['messages'][0]['id'])) {
            $record = WhatsappMessage::create([
                'text' => isset($apiMessage['text']) && isset($apiMessage['text']['body']) ? $apiMessage['text']['body'] : null,
                'contact_phone_number' => $messageable->{config('whatsapp.database.messageable_phone_column')},
                (config('whatsapp.database.messageable_id_column')) => $messageable->id,
                'type' => $type,
                'whatsapp_message_id' => $body['messages'][0]['id'],
                'way' => MessageWay::OUTBOUND,
                'ctx_type' => isset($apiMessage['context']['message_id']) ? ContextType::REPLY : null,
                'ctx' => $apiMessage['context']['message_id'] ?? null,
            ]);

            Log::info('Message record created', Arr::only($record->toArray(), ['id', 'whatsapp_message_id']));
        }
    }

    /**
     * setMesgCtx
     *
     * Sets the context for the API message.
     *
     * @param  string  $whatsappPhone  The recipient's WhatsApp phone number
     * @param  ?WhatsappMessage  $replyTo  The message to which this message is a reply
     * @return array The API message context
     */
    private function setMesgCtx(string $whatsappPhone, ?WhatsappMessage $replyTo = null): array {
        $msg = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $whatsappPhone,
        ];

        if ($replyTo) {
            $msg['context'] = ['message_id' => $replyTo->whatsapp_message_id];
        }

        return $msg;
    }

    /**
     * createTextMsg
     *
     * Creates a text message payload for WhatsApp API.
     *
     * @param  array  $msg  The API message payload with common fields set
     * @param  string  $text  The text message content
     * @return array The text message payload
     */
    private function createTextMsg(array $msg, string $text): array {
        return array_merge($msg, [
            'text' => ['body' => $text],
            'type' => MessageType::TEXT->value,
        ]);
    }

    /**
     * createReactionMsg
     *
     * Creates a reaction message payload for WhatsApp API.
     *
     * @param  array  $msg  The API message payload with common fields set
     * @param  array  $data  The reaction message data
     * @return array The reaction message payload
     */
    private function createReactionMsg(array $msg, array $data): array {
        $msg['type'] = MessageType::REACTION->value;
        $msg['reaction'] = [];
        $msg['reaction']['message_id'] = $data['message_id'];

        $msg['reaction']['emoji'] = $data['emoji'] ?? '';

        return $msg;
    }

    /**
     * updateMessageRecordWithReaction
     *
     * Updates the message record in the database with the reaction information.
     *
     * @param  array  $apiMessage  The original API message payload containing the reaction data
     */
    private function updateMessageRecordWithReaction(array $apiMessage): void {
        $reactedToMsg = WhatsappMessage::where('whatsapp_message_id', $apiMessage['reaction']['message_id'])
            ->first();

        $reaction = is_null($reactedToMsg->reaction) ? [] : $reactedToMsg->reaction;

        if ($apiMessage['reaction']['emoji']) {
            $reaction[] = [
                'emoji' => $apiMessage['reaction']['emoji'],
                'from' => $this->businessPhoneNumber,
            ];
        } else {
            $reaction = array_filter($reaction, fn ($reaction) => $reaction['from'] !== $this->businessPhoneNumber);
        }

        $reactedToMsg->update(['reaction' => $reaction]);
    }
}
