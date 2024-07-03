<?php

namespace The42dx\Whatsapp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use The42dx\Whatsapp\Rules\{HubMode, VerifyToken};

/**
 * WebhookCheckRequest
 *
 * Request object for the webhook check endpoint.
 *
 */
class WebhookCheckRequest extends FormRequest {
    public function rules() {
        return [
            'hub_challenge'    => 'required|string',
            'hub_mode'         => ['required', 'string', new HubMode()],
            'hub_verify_token' => ['required', 'string', new VerifyToken()],
        ];
    }
}
