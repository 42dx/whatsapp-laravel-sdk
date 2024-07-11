<?php

namespace The42dx\Whatsapp\Enums;

/**
 * enum ContactPropType
 *
 * Represents the type of the contact property
 *
 * @package The42dx\Whatsapp\Enums
 */
enum ContactPropType: string {
    case CELL   = 'CELL';
    case HOME   = 'HOME';
    case IPHONE = 'IPHONE';
    case MAIN   = 'MAIN';
    case OTHER  = 'OTHER';
    case WORK   = 'WORK';
}
