<?php

namespace The42dx\Whatsapp\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use The42dx\Whatsapp\Models\Traits\CanSendWhatsappMsg;

class User extends Model {
    use CanSendWhatsappMsg;

    protected $fillable = ['phone'];
}
