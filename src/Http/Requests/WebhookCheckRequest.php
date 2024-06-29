<?php

namespace The42dx\Whatsapp\Http\Requests;

use Illuminate\Http\Request;

/**
 * WebhookCheckRequest
 *
 * Request object for the webhook check endpoint.
 *
 */
class WebhookCheckRequest extends Request {
    public string $hub_challenge;
    public string $hub_mode;
    public string $hub_verify_token;
}
