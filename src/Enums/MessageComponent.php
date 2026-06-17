<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum MessageComponent
 *
 * Represents the component of a message
 */
enum MessageComponent: string implements Enum {
    case BODY = 'body';
    case FOOTER = 'footer';
    case HEADER = 'header';
    case BUTTON = 'button';

    /**
     * General subtypes
     */
    case TEXT = 'text';

    /**
     * BUTTON subtypes
     */
    case COPY_CODE = 'copy_code';
    case COUPON_CODE = 'coupon_code';
}
