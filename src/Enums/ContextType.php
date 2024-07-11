<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum ContextType
 *
 * Represents the context of the message
 *
 * @package The42dx\Whatsapp\Enums

 */
enum ContextType: string implements Enum {
    case F_FWD = 'FREQUENTLY_FORWARDED_MESSAGE';
    case FWD   = 'FORWARDED_MESSAGE';
    case STD   = 'STANDARD_MESSAGE';
}
