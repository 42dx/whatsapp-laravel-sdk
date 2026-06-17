<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum ContextType
 *
 * Represents the context of the message
 */
enum ContextType: string implements Enum {
    case F_FWD = 'frequently_forwarded_message';
    case FWD = 'forwarded_message';
    case REPLY = 'reply';
}
