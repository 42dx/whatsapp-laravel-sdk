<?php

namespace The42dx\Whatsapp\Enums;

enum MessageStatus: string {
    case DELETED   = 'deleted';
    case DELIVERED = 'delivered';
    case FAILED    = 'failed';
    case PENDING   = 'pending';
    case READ      = 'read';
    case SENT      = 'sent';
    case WARNING   = 'warning';
}
