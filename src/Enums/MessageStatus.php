<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum MessageStatus
 *
 * Represents the status of a message sent to/from the Whatsapp Business API
 *
 * @package The42dx\Whatsapp\Enums
 */
enum MessageStatus: string implements Enum {
    case DELETED   = 'deleted';
    case DELIVERED = 'delivered';
    case FAILED    = 'failed';
    case PENDING   = 'pending';
    case READ      = 'read';
    case SENT      = 'sent';
    case WARNING   = 'warning';
}
