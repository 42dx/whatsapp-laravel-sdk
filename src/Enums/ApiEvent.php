<?php

namespace The42dx\Whatsapp\Enums;

use The42dx\Whatsapp\Contracts\Enum;

/**
 * enum ApiEvent
 *
 * Represents the event type of the API event sent
 *
 * @package The42dx\Whatsapp\Enums
 */
enum ApiEvent: string implements Enum {
    case ACC_ALERTS                = 'account_alerts';
    case ACC_REVIEW_UPDATE         = 'account_review_update';
    case ACC_UPDT                  = 'account_update';
    case BUSINESS_CAPABILITY_UPDT  = 'business_capability_update';
    case BUSINESS_STATUS_UPDT      = 'business_status_update';
    case CAMPAIGN_STATUS_UPDT      = 'campaign_status_update';
    case FLOWS                     = 'flows';
    case MSG_ECHOES                = 'message_echoes';
    case MSG_HANDOVERS             = 'messaging_handovers';
    case MSG_TPLT_QUALITY_UPDT     = 'message_template_quality_update';
    case MSG_TPLT_STATUS_UPDT      = 'message_template_status_update';
    case MSGS                      = 'messages';
    case PARTNER_SOLUTIONS         = 'partner_solutions';
    case PHONE_NUM_NAME_UPDT       = 'phone_number_name_update';
    case PHONE_NUM_QUALITY_UPDT    = 'phone_number_quality_update';
    case SECURITY                  = 'security';
    case TEMPLATE_CAT_UPDT         = 'template_category_update';
}
