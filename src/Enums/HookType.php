<?php

namespace The42dx\Whatsapp\Enums;

enum HookType: string {
    case ACC_ALERT                 = 'account_alerts';
    case ACC_REVIEW_UPDT           = 'account_review_update';
    case ACC_UPDT                  = 'account_update';
    case BUSINESS_CAP_UPDT         = 'business_capability_update';
    case BUSINESS_STATUS_UPDT      = 'business_status_update';
    case CAMPAIGN_STATUS_UPDT      = 'campaign_status_update';
    case FLOWS                     = 'flows';
    case MSG                       = 'messages';
    case MSG_ECHO                  = 'message_echoes';
    case MSG_HANDOVER              = 'messaging_handovers';
    case MSG_TEMPLATE_QUALITY_UPDT = 'message_template_quality_update';
    case MSG_TEMPLATE_STATUS_UPDT  = 'message_template_status_update';
    case PHONE_NUM_NAME_UPDT       = 'phone_number_name_update';
    case PHONE_NUM_QUALITY_UPDT    = 'phone_number_quality_update';
    case SECURITY                  = 'security';
    case TEMPLATE_CAT_UPDT         = 'template_category_update';
}
