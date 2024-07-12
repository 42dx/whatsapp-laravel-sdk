<?php

namespace The42dx\Whatsapp\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\EventEntity;
use The42dx\Whatsapp\Http\Requests\ApiEventRequest;
use The42dx\Whatsapp\Http\Requests\WebhookCheckRequest;

/**
 * WebhookController
 *
 * Controller responsible for handling Whatsapp webhook actions and data.
 *
 */
class WebhookController extends Controller {
    /**
     * ERROR_INVALID_VERIFY
     *
     * Error message for invalid verify token
     *
     * @var string
     */
    const ERROR_INVALID_VERIFY = 'Invalid verify token';

    /**
     * check
     *
     * Endpoint handler for the Whatsapp Business API webhook subscription check.
     *
     * @param  \The42dx\Whatsapp\Http\Requests\WebhookCheckRequest $request The request object
     * @return \Illuminate\Http\Response The response object containing the challenge sent
     */
    public function check(WebhookCheckRequest $request): Response {
        Log::debug('Whatsapp verify_token match');

        return response($request->hub_challenge);
    }

    public function handle(ApiEventRequest $request) {
        Log::debug('Whatsapp event received: ' . json_encode($request->all()), );
    }
}
