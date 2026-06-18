<?php

namespace The42dx\Whatsapp\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Support\Arr;
use The42dx\Whatsapp\Database\Factories\WhatsappMessageFactory;
use The42dx\Whatsapp\Enums\{ContextType, MessageType};

/**
 * WhatsappMessage
 *
 * Represents a Whatsapp message data on the database.
 */
#[UseFactory(WhatsappMessageFactory::class)]
class WhatsappMessage extends Model {
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_phone_number',
        'deleted_at',
        'delivered_at',
        'payload',
        'read_at',
        'sent_at',
        'status',
        'text',
        'type',
        'way',
        'whatsapp_message_id',
    ];

    /**
     * Get the fillable attributes for the model.
     */
    public function getFillable(): array {
        return array_values(array_unique([
            ...parent::getFillable(),
            config('whatsapp.database.messageable_id_column', 'user_id'),
        ]));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return ['payload' => 'array'];
    }

    /**
     * getPayloadType
     *
     * Get the payload items of the message that match the given type.
     *
     * @param  array|ContextType|MessageType  $type  The type or types to filter the payload items by.
     * @return array The payload items that match the given type.
     */
    public function getPayloadType(array|ContextType|MessageType $type): array {
        $haystack = $type instanceof MessageType || $type instanceof ContextType ? [$type->value] : Arr::map($type, fn($tp) => $tp->value);

        return array_filter($this->payload ?? [], fn($item) => in_array($item['type'], $haystack));
    }

    /**
     * getPayloadWithoutType
     *
     * Get the payload items of the message that do not match the given type.
     *
     * @param  array|ContextType|MessageType  $type  The type or types to filter the payload items by.
     * @return array The payload items that do not match the given type.
     */
    public function getPayloadWithoutType(array|ContextType|MessageType $type): array {
        $haystack = $type instanceof MessageType || $type instanceof ContextType ? [$type->value] : Arr::map($type, fn($tp) => $tp->value);

        return array_filter($this->payload ?? [], fn($item) => !in_array($item['type'], $haystack));
    }
}
