<?php

namespace The42dx\Whatsapp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use The42dx\Whatsapp\Database\Factories\WhatsappMessageFactory;

/**
 * WhatsappMessage
 *
 * Represents a Whatsapp message data on the database.
 */
class WhatsappMessage extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_phone_number',
        'ctx_type',
        'ctx',
        'deleted_at',
        'delivered_at',
        'reaction',
        'read_at',
        'sent_at',
        'status',
        'text',
        'type',
        'user_id',
        'way',
        'whatsapp_message_id',
    ];

    /**
     * newFactory
     *
     * Create a new factory instance for the model.
     */
    public static function newFactory(): WhatsappMessageFactory {
        return WhatsappMessageFactory::new();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'reaction' => 'array',
        ];
    }
}
