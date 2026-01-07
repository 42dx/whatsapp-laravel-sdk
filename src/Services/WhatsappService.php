<?php

namespace The42dx\Whatsapp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Enums\{MessageType, MessageWay};
use The42dx\Whatsapp\Models\WhatsappMessage;
use Throwable;

class WhatsappService {
    private Client $http;

    private string $apiVersion;

    private string $businessPhoneId;

    private string $serverUrl;

    private string $token;

    public function __construct() {
        $this->serverUrl = config('whatsapp.server_url');
        $this->apiVersion = config('whatsapp.api_version');
        $this->businessId = config('whatsapp.business_id');
        $this->businessPhoneId = config('whatsapp.business_phone_id');
        $this->token = config('whatsapp.token');
        $this->http = new Client([
            'base_uri' => "{$this->serverUrl}/{$this->apiVersion}/",
            'headers' => ['Authorization' => "Bearer {$this->token}"],
        ]);
    }

    public function send(MessageType $type, Model $user, array|string $data): array {
        if (is_null($user->{config('whatsapp.database.user_phone_column')})) {
            Log::error('User does not have a phone number set', ['user_id' => $user->id]);

            return [];
        }

        $apiMessage = $this->getApiMessage($type, $user->{config('whatsapp.database.user_phone_column')}, $data);

        try {
            $response = $this->http->post("{$this->businessPhoneId}/messages", ['json' => $apiMessage]);
            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('Message sent to WhatsApp API', ['response' => $body]);

            if (isset($body['messages']) && isset($body['messages'][0]) && isset($body['messages'][0]['id'])) {
                WhatsappMessage::create([
                    'text' => $apiMessage['text']['body'] ?? null,
                    'contact_phone_number' => $user->{config('whatsapp.database.user_phone_column')},
                    'user_id' => $user->id,
                    'type' => $type,
                    'whatsapp_message_id' => $body['messages'][0]['id'],
                    'way' => MessageWay::OUTBOUND,
                ]);
            }
        } catch (RequestException $th) {
            $body = [];

            Log::error('Error sending whatsapp message', [
                'body' => $th->getResponse()->getBody()->getContents(),
                'error' => $th->getMessage(),
            ]);
        } catch (Throwable $th) {
            $body = [];

            Log::error('Unknown error (Exception needs handling)', [
                'error' => $th->getMessage(),
                'exception_class' => get_class($th),
            ]);
        }

        return $body;
    }

    private function getApiMessage(MessageType $type, string $whatsappPhone, array|string $data): ?array {
        switch ($type) {
            case MessageType::TEXT:
                return $this->createTextMsg($whatsappPhone, $data);
            case MessageType::AUDIO:
            case MessageType::CONTACTS:
            case MessageType::DOCUMENT:
            case MessageType::IMAGE:
            case MessageType::INTERACTIVE:
            case MessageType::LOCATION:
            case MessageType::REACTION:
            case MessageType::STICKER:
            case MessageType::TEMPLATE:
            case MessageType::UNSUPPORTED:
            case MessageType::VIDEO:
            default:
                Log::warning('Unsupported message type: ' . $type->value);
                Log::debug('Unsupported message content:', ['content' => $data]);

                return null;
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
