<?php

namespace The42dx\Whatsapp\Models\Traits;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Services\WhatsappService;

/**
 * CanSendWhatsappMsg
 *
 * Trait to enable sending WhatsApp messages of various types.
 */
trait CanSendWhatsappMsg {
    /**
     * sendWhatsappMsg
     *
     * Send a WhatsApp message of the specified type with the given data.
     *
     * @param  MessageType  $type  The type of WhatsApp message to send.
     * @param  array|string  $data  The content or data of the message. Can be a string for simple text messages or an array for more complex message types.
     * @param  WhatsappMessage|null  $replyTo  The message to which this message is a reply.
     */
    public function sendWhatsappMsg(MessageType $type, array|string $data, ?WhatsappMessage $replyTo = null): void {
        $replyToId = $replyTo ? $replyTo->whatsapp_message_id : null;
        $apiMsg = WhatsappApiMessage::compose($this->{config('whatsapp.database.messageable_phone_column')})
            ->replyTo(msg: $replyToId);

        switch ($type) {
            case MessageType::TEXT:
                $apiMsg->with(text: $data);
                break;
            case MessageType::REACTION:
                $apiMsg->reactTo(msg: $replyToId, with: $data);
                break;
            case MessageType::TEMPLATE:
                if (!is_array($data) || empty($data['name'])) {
                    throw new InvalidArgumentException('Template message data must include a name.');
                }

                $apiMsg->usingTemplate(name: $data['name'], langCode: $data['lang'] ?? null);
                $this->handleTemplateComponents($apiMsg, $data['components'] ?? []);
                break;
            case MessageType::AUDIO:
            case MessageType::BUTTON:
            case MessageType::CONTACTS:
            case MessageType::DOCUMENT:
            case MessageType::IMAGE:
            case MessageType::INTERACTIVE:
            case MessageType::LOCATION:
            case MessageType::STICKER:
            case MessageType::VIDEO:
            default:
                Log::warning('Unsupported message type: ' . $type->value);
                Log::debug('Unsupported message type:', ['type' => $type->value, 'data' => $data]);

                return;
        }

        app(WhatsappService::class)->send($apiMsg, $this);
    }

    /**
     * handleTemplateComponents
     *
     * Validates and process template message components.
     *
     * @param  WhatsappApiMessage  $msg  Whatsapp API message instance.
     * @param  array|string  $components  The template message components.
     */
    private function handleTemplateComponents(WhatsappApiMessage $msg, ?array $components = []): void {
        if (isset($components) && !is_null($components) && is_array($components)) {
            foreach ($components as $component) {
                $msg->withComponent(
                    type: $component['type'],
                    subType: $component['subType'] ?? null,
                    index: $component['index'] ?? null,
                    params: $component['parameters']
                );
            }
        }
    }
}
