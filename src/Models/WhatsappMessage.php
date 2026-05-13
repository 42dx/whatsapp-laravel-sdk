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

    public static function newFactory(): WhatsappMessageFactory {
        return WhatsappMessageFactory::new();
    }

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
        'read_at',
        'sent_at',
        'status',
        'text',
        'type',
        'user_id',
        'way',
        'whatsapp_message_id',
    ];
}
