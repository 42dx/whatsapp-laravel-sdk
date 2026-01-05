<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum StickerType
 *
 * Represents the type of sticker
 */
enum StickerType: string implements Enum {
    case STATIC = 'STATIC';
    case ANIMATED = 'ANIMATED';
}
