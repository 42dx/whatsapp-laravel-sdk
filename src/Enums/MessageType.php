<?php

namespace The42dx\Whatsapp\Enums;

/**
 * enum MessageType
 *
 * Represents the type of message sent to/from the Whatsapp Business API
 *
 * @package The42dx\Whatsapp\Enums
 */
enum MessageType: string {
    case AUDIO       = 'audio';
    case CONTACTS    = 'contacts';
    case DOCUMENT    = 'document';
    case IMAGE       = 'image';
    case INTERACTIVE = 'interactive';
    case LOCATION    = 'location';
    case REACTION    = 'reaction';
    case STICKER     = 'sticker';
    case TEMPLATE    = 'template';
    case TEXT        = 'text';
    case UNSUPPORTED = 'unsupported';
    case VIDEO       = 'video';
}
