<?php

namespace The42dx\Whatsapp\Enums;

enum ContextType: string {
    case F_FWD = 'FREQUENTLY_FORWARDED_MESSAGE';
    case FWD   = 'FORWARDED_MESSAGE';
    case STD   = 'STANDARD_MESSAGE';
}
