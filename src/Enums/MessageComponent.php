<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum MessageComponent
 *
 * Represents the component of a message
 *
 * @package The42dx\Whatsapp\Enums
 */
enum MessageComponent: string implements Enum {
    case BODY   = 'body';
    case FOOTER = 'footer';
    case HEADER = 'header';
}
