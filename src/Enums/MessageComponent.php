<?php

namespace The42dx\Whatsapp\Enums;

/**
 * enum MessageComponent
 *
 * Represents the component of a message
 *
 * @package The42dx\Whatsapp\Enums
 */
enum MessageComponent: string {
    case BODY   = 'body';
    case FOOTER = 'footer';
    case HEADER = 'header';
}
