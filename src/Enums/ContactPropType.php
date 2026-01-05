<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum ContactPropType
 *
 * Represents the type of the contact property
 */
enum ContactPropType: string implements Enum {
    case CELL = 'CELL';
    case HOME = 'HOME';
    case IPHONE = 'IPHONE';
    case MAIN = 'MAIN';
    case OTHER = 'OTHER';
    case WORK = 'WORK';
}
