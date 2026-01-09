<?php

namespace The42dx\Whatsapp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use The42dx\Whatsapp\Enums\{MessageType, MessageWay};
use The42dx\Whatsapp\Models\WhatsappMessage;

class WhatsappService {
    private Client $http;

    private string $apiVersion;

    private string $businessPhoneId;

    private string $serverUrl;

    private string $token;

    public function __construct(?Client $http = null) {
        if (empty(config('whatsapp.server_url')) || empty(config('whatsapp.api_version')) || empty(config('whatsapp.business_phone_id')) || empty(config('whatsapp.token'))) {
            Log::error('WhatsApp configuration is missing or invalid. Please check your configuration settings.');

            throw new InvalidArgumentException('WhatsApp configuration is missing or invalid.');
        }

        $this->serverUrl = config('whatsapp.server_url');
        $this->apiVersion = config('whatsapp.api_version');
        $this->businessPhoneId = config('whatsapp.business_phone_id');
        $this->token = config('whatsapp.token');
        $this->http = $http ?? new Client([
            'base_uri' => "{$this->serverUrl}/{$this->apiVersion}/",
            'headers' => ['Authorization' => "Bearer {$this->token}"],
        ]);
    }

    public function send(MessageType $type, Model $user, array|string $data): array {
        $body = [];

        if (is_null($user->{config('whatsapp.database.user_phone_column')})) {
            Log::warning('User does not have a phone number set', ['user_id' => $user->id]);

            return $body;
        }

        $apiMessage = $this->getApiMessage($type, $user->{config('whatsapp.database.user_phone_column')}, $data);

        if (!$apiMessage) {
            return $body;
        }

        try {
            $response = $this->http->post("{$this->businessPhoneId}/messages", ['json' => $apiMessage]);
            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('Message sent to WhatsApp API', ['response' => $body]);

            $this->createMessageRecord($body, $user, $type, $apiMessage);
        } catch (RequestException $th) {
            Log::error('Error sending whatsapp message', [
                'body' => $th->getResponse()->getBody()->getContents(),
                'error' => $th->getMessage(),
            ]);
        }

        return $body;
    }

    private function getApiMessage(MessageType $type, string $whatsappPhone, array|string $data): ?array {
        switch ($type) {
            case MessageType::TEXT:
                return $this->createTextMsg($whatsappPhone, $data);
            case MessageType::AUDIO:
            case MessageType::BUTTON:
            case MessageType::CONTACTS:
            case MessageType::DOCUMENT:
            case MessageType::IMAGE:
            case MessageType::INTERACTIVE:
            case MessageType::LOCATION:
            case MessageType::REACTION:
            case MessageType::STICKER:
            case MessageType::TEMPLATE:
            case MessageType::VIDEO:
            default:
                Log::warning('Unsupported message type: ' . $type->value);
                Log::debug('Unsupported message content:', ['content' => $data]);

                return null;
        }
    }

    private function createMessageRecord(array $body, Model $user, MessageType $type, array $apiMessage): void {
        if (isset($body['messages']) && isset($body['messages'][0]) && isset($body['messages'][0]['id'])) {
            $record = WhatsappMessage::create([
                'text' => isset($apiMessage['text']) && isset($apiMessage['text']['body']) ? $apiMessage['text']['body'] : null,
                'contact_phone_number' => $user->{config('whatsapp.database.user_phone_column')},
                'user_id' => $user->id,
                'type' => $type,
                'whatsapp_message_id' => $body['messages'][0]['id'],
                'way' => MessageWay::OUTBOUND,
            ]);

            Log::info('Message record created', Arr::only($record->toArray(), ['id', 'whatsapp_message_id']));
        }
    }

    private function createTextMsg(string $to, string $text): array {
        return [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'text' => ['body' => $text],
            'to' => $to,
            'type' => MessageType::TEXT->value,
        ];
    }
}
