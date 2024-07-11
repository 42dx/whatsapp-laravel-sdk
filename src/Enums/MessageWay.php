<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum MessageWay
 *
 * Represents the way of the message, being inbound for messages sent TO the business API number,
 * or outbound for messages sent FROM the business API number
 *
 * @package The42dx\Whatsapp\Enums
 */
enum MessageWay: string implements Enum {
    case INBOUND = 'inbound';
    case OUTBOUND = 'outbound';
}
