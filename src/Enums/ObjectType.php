<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum ObjectType
 *
 * Represents the type of object on facebook graph API
 */
enum ObjectType: string implements Enum {
    case WHATSAPP_BUSINESS_API_ACC = 'whatsapp_business_account';
}
