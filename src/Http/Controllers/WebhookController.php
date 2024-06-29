<?php

namespace The42dx\Whatsapp\Http\Controllers;

use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Http\Requests\WebhookCheckRequest;

/**
 * WebhookController
 *
 * Controller responsible for handling Whatsapp webhook actions and data.
 *
 */
class WebhookController extends Controller {
    /**
     * ERROR_MSG_INVALID_VERIFY
     *
     * Error message for invalid verify token
     *
     * @var string
     */
    const ERROR_MSG_INVALID_VERIFY = 'Invalid verify token';

    /**
     * check
     *
     * Endpoint handler for the Whatsapp Business API webhook subscription check.
     *
     * @param  \The42dx\Whatsapp\Http\Requests\WebhookCheckRequest $request The request object
     * @return \Illuminate\Http\Response The response object containing the challenge sent
     * @throws \Illuminate\Http\Client\HttpClientException If the verify token is invalid
     */
    public function check(WebhookCheckRequest $request): Response {
        Log::debug('Whatsapp remote verify_token' . $request->hub_verify_token);
        Log::debug('Whatsapp local verify_token' . config('whatsapp.webhook_verify'));

        if ($request->hub_verify_token !== config('whatsapp.webhook_verify')) {
            Log::error(
                'Whatsapp verify_token does not match match',
                [
                    'remote-token' => $request->hub_verify_token,
                    'local-token' => config('whatsapp.webhook_verify')
                ]
            );

            throw new HttpClientException(self::ERROR_MSG_INVALID_VERIFY, Response::HTTP_FORBIDDEN);
        }

        Log::debug('Whatsapp verify_token match');

        return response($request->hub_challenge);
    }
}
