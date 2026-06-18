<?php

namespace The42dx\Whatsapp\Factories;

use Illuminate\Support\{Arr, Collection};
use The42dx\Whatsapp\Contracts\Enum;
use The42dx\Whatsapp\Enums\{MessageComponent, MessageType};

class WhatsappApiMessage {
    /**
     * messaging_product
     *
     * The messaging product (e.g., "whatsapp")
     */
    private $messaging_product = 'whatsapp';

    /**
     * recipient_type
     *
     * The type of recipient (e.g., "individual, group")
     */
    private $recipient_type = 'individual';

    /**
     * to
     *
     * The recipient's phone number in international format (e.g., "1234567890")
     */
    private string $to;

    /**
     * type
     *
     * The type of message being sent (e.g., "text", "image", "template", etc.)
     */
    private MessageType $type;

    /**
     * context
     *
     * The context of the message, used for replying to a specific message.
     * It should contain the message_id of the message being replied to.
     */
    private array $context;

    /**
     * text
     *
     * The text body of the message in (whatsapp limited) markdown format.
     */
    private array $text;

    /**
     * reaction
     *
     * Reaction (emote) object
     */
    private array $reaction;

    /**
     * template
     *
     * Whatsapp message template data
     */
    private array $template;

    public function __construct(null|int|string $to = null) {
        $this->setRecipient($to);
    }

    /**
     * __get
     *
     * Get the entity attribute
     *
     * @param  string  $key  The entity attribute
     * @return mixed The entity attribute value
     */
    public function __get(string $key): mixed {
        return $this->$key;
    }

    /**
     * __isset
     *
     * @param  string  $key  The entity attribute
     * @return bool The existence check result
     */
    public function __isset(string $key): bool {
        return array_key_exists($key, $this->toArray());
    }

    /**
     * compose
     *
     * Factory method to create a new instance of the class. Alias to make method.
     *
     * @param  null|int|string  $to  The recipient's phone number in international format (e.g., "1234567890")
     */
    public static function compose(null|int|string $to): self {
        return new self($to);
    }

    /**
     * make
     *
     * Factory method to create a new instance of the class.
     *
     * @param  null|int|string  $to  The recipient's phone number in international format (e.g., "1234567890")
     */
    public static function make(null|int|string $to): self {
        return new self($to);
    }

    /**
     * toArray
     *
     * Convert the class to an array
     *
     * @see \Illuminate\Support\Collection
     * @see \The42dx\Whatsapp\Contracts\Entity
     */
    public function toArray(): array {
        $array = [];

        foreach ($this as $key => $value) {
            if ($value instanceof Enum) {
                $array[$key] = $value->value;

                continue;
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * toJson
     *
     * Convert the entity to a JSON string
     */
    public function toJson(): string {
        return json_encode($this->toArray());
    }

    /**
     * to
     *
     * Set the recipient's phone number. Alias to setRecipient method.
     *
     * @param  int|string  $to  The recipient's phone number in international format (e.g., "1234567890")
     */
    public function to(int|string $to): self {
        $this->setRecipient(to: $to);

        return $this;
    }

    /**
     * replyTo
     *
     * Reply to a specific message
     *
     * @param  string  $msg  The ID of the message being replied to. Alias to setContext method.
     */
    public function replyTo(?string $msg = null): self {
        $this->setContext(messageId: $msg);

        return $this;
    }

    /**
     * withText
     *
     * Set the text of the message in (whatsapp limited) markdown format. Alias to setText method.
     *
     * @param  ?string  $text  The text body of the message in (whatsapp limited) markdown format.
     */
    public function withText(?string $text): self {
        $this->setText(text: $text);

        return $this;
    }

    /**
     * with
     *
     * Set the text of the message in (whatsapp limited) markdown format. Alias to setText method.
     *
     * @param  ?string  $text  The text body of the message in (whatsapp limited) markdown format.
     */
    public function with(?string $text): self {
        $this->setText(text: $text);

        return $this;
    }

    /**
     * reactTo
     *
     * React to the provided whatsapp message. Alias to setReaction method.
     *
     * @param  string  $msg  The message ID to react to.
     * @param  string  $with  The emoji to be used as reaction.
     */
    public function reactTo(string $msg, string $with = ''): self {
        $this->setReaction(msgId: $msg, emoji: $with);

        return $this;
    }

    /**
     * usingTemplate
     *
     * Set the template for the whatsapp message. Alias to setTemplate method.
     *
     * @param  string  $name  The name of the template.
     * @param  string  $langCode  Template language code.
     */
    public function usingTemplate(string $name, ?string $langCode = null): self {
        $this->setTemplate(name: $name, langCode: $langCode);

        return $this;
    }

    /**
     * withComponent
     *
     * Creates the template message component structure following whatsapp api specs.
     * Alias to setTemplateComponent method
     *
     * @param  MessageComponent  $type  Collection of params for the component.
     * @param  array  $params  Collection of params for the component.
     * @param  ?MessageComponent  $subType  Subtype of the component, when applicable.
     * @param  ?int  $index  Index of the component, when applicable.
     */
    public function withComponent(MessageComponent $type, array $params, ?MessageComponent $subType = null, ?int $index = null): self {
        $this->setTemplateComponent(type: $type, params: $params, subType: $subType, index: $index);

        return $this;
    }

    /**
     * setRecipient
     *
     * Set the recipient's phone number
     *
     * @param  int|string  $to  The recipient's phone number in international format (e.g., "1234567890")
     */
    private function setRecipient(null|int|string $to): void {
        if ((bool) $to && !is_null($to) && !empty($to)) {
            $this->to = (string) $to;
        }
    }

    /**
     * setType
     *
     * Set the type of the message
     *
     * @param  \The42dx\Whatsapp\Enums\MessageType  $type  The type of message being sent (e.g., "text", "image", "template", etc.)
     */
    private function setType(MessageType $type): void {
        $this->type = $type;
    }

    /**
     * setContext
     *
     * Set the context of the message
     *
     * @param  ?string  $messageId  The ID of the message being replied to
     */
    private function setContext(?string $messageId): void {
        if ((bool) $messageId && !is_null($messageId) && !empty($messageId)) {
            $this->context = ['message_id' => $messageId];
        }
    }

    /**
     * setText
     *
     * Set the text body of the message in (whatsapp limited) markdown format.
     *
     * @param  ?string  $text  The text body of the message in (whatsapp limited) markdown format.
     */
    private function setText(string $text): void {
        if ((bool) $text && !is_null($text) && !empty($text)) {
            $this->setType(MessageType::TEXT);

            $this->text = ['body' => $text];
        }
    }

    /**
     * setReaction
     *
     * React to the provided whatsapp message. Alias to setReaction method.
     *
     * @param  string  $msgId  The message ID to react to.
     * @param  string  $emoji  The emoji to be used as reaction.
     */
    private function setReaction(string $msgId, string $emoji): void {
        if (!is_null($msgId) && is_string($emoji) && (bool) $msgId && !is_null($msgId) && !empty($msgId)) {
            $this->setType(MessageType::REACTION);

            $this->reaction = [
                'message_id' => $msgId,
                'emoji' => $emoji,
            ];
        }
    }

    /**
     * setTemplate
     *
     * Set the template for the whatsapp message
     *
     * @param  string  $name  The name of the template.
     * @param  string  $langCode  Template language code.
     */
    private function setTemplate(?string $name = null, ?string $langCode = null): void {
        $this->setType(MessageType::TEMPLATE);
        $this->template = $this->template ?? ['language' => ['code' => config('whatsapp.template_lang')]];

        if ((bool) $name && !is_null($name) && !empty($name)) {
            $this->template['name'] = $name;
        }

        if ((bool) $langCode && !is_null($langCode) && !empty($langCode)) {
            $this->template['language']['code'] = $langCode;
        }
    }

    /**
     * generateNewTemplateComponent
     *
     * Creates the template message component structure following whatsapp api specs
     *
     * @param  MessageComponent  $type  Collection of params for the component.
     * @param  array  $params  Collection of params for the component.
     * @param  ?MessageComponent  $subType  Subtype of the component, when applicable.
     * @param  ?int  $index  Index of the component, when applicable.
     */
    private function setTemplateComponent(MessageComponent $type, array $params, ?MessageComponent $subType = null, ?int $index = null): void {
        if (isset($type) && $type instanceof MessageComponent) {
            $this->setTemplate();

            $this->template['components'] = isset($this->template['components']) && !empty($this->template['components'])
                ? $this->template['components']
                : [];

            $hasComponentType = Arr::first($this->template['components'], fn($component) => $component['type'] === $type->value, []);

            $newComponent = array_merge(
                $hasComponentType,
                $this->generateNewTemplateComponent(
                    type: $type,
                    params: $params,
                    subType: $subType,
                    index: $index
                )
            );

            $this->template['components'][] = $newComponent;
        }
    }

    /**
     * generateNewTemplateComponent
     *
     * Generate the template message component following whatsapp api specs
     *
     * @param  MessageComponent  $type  Collection of params for the component.
     * @param  array  $params  Collection of params for the component.
     * @param  ?MessageComponent  $subType  Subtype of the component, when applicable.
     * @param  ?int  $index  Index of the component, when applicable.
     */
    private function generateNewTemplateComponent(MessageComponent $type, array $params, ?MessageComponent $subType = null, ?int $index = null): array {
        return [
            'type' => $type->value,
            'sub_type' => isset($subType) && $subType instanceof MessageComponent
                ? $subType->value
                : null,
            'index' => isset($index) && is_int($index)
                ? $index : null,
            'parameters' => array_merge(
                $hasComponentType['parameters'] ?? [],
                $this->generateComponentParameter($params)
            ),
        ];
    }

    /**
     * generateComponentParameter
     *
     * Generate parameters following whatsapp api specs for a given message component
     *
     * @param  array  $params  Collection of params for the component.
     */
    private function generateComponentParameter(array $params): array {
        return Arr::map($params, fn($param) => [
            'type' => isset($param['type']) && $param['type'] instanceof MessageComponent
                ? $param['type']->value
                : MessageComponent::TEXT->value,
            'parameter_name' => isset($param['name']) && !empty($param['name'])
                ? $param['name']
                : null,
            'coupon_code' => isset($param['couponCode']) && !empty($param['couponCode'])
                ? $param['couponCode']
                : null,
            'text' => $param['text'] ?? '',
        ]);
    }
}
