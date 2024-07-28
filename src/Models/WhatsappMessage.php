<?php

use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'api_phone_number',
        'contact_phone_number',
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
