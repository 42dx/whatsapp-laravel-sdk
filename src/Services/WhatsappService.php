<?php

namespace The42dx\Whatsapp\Services;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Enums\MessageType;
use Throwable;

class WhatsappService {
    private Client $http;
    private string $apiVersion;
    private string $businessId;
    private string $businessPhoneId;
    private string $serverUrl;
    private string $token;

    public function __construct() {
        $this->serverUrl       = config('whatsapp.server_url');
        $this->apiVersion      = config('whatsapp.api_version');
        $this->businessId      = config('whatsapp.business_id');
        $this->businessPhoneId = config('whatsapp.business_phone_id');
        $this->token           = config('whatsapp.token');
        $this->http            = new Client([
            'base_uri' => "{$this->serverUrl}/{$this->apiVersion}/",
            'headers'  => ['Authorization' => "Bearer {$this->token}"]
        ]);
    }

    public function send(MessageType $type, string $whatsappPhone, string|array $data): array {
        $apiMessage = $this->getApiMessag($type, $whatsappPhone, $data);

        try {
            $response = $this->http->post("{$this->businessPhoneId}/messages", ['json' => $apiMessage]);
            $body     = json_decode($response->getBody()->getContents(), true);

            Log::info('Message sent to WhatsApp API', ['response' => $body]);
        } catch (RequestException $th) {
            $body = [];

            Log::error('Error sending whatsapp message', [
                'body'  => $th->getResponse()->getBody()->getContents(),
                'error' => $th->getMessage(),
            ]);
        } catch (\Throwable $th) {
            $body = [];

            Log::error('Unknown error (Exception needs handling)', [
                'error'           => $th->getMessage(),
                'exception_class' =>  get_class($th),
            ]);
        }

        return $body;
    }

    private function getApiMessag(MessageType $type, string $whatsappPhone, string|array $data): array {
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
        }
    }

    private function createTextMsg(string $to, string $text): array {
        return [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'text'              => ['body' => $text],
            'to'                => $to,
            'type'              => MessageType::TEXT->value,
        ];
    }
}
