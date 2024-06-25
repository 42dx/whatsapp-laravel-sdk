<?php

namespace The42dx\Whatsapp\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller {
    public function check() {
        if (request()->hub_verify_token !== config('whatsapp.webhook_verify')) {
            Log::info(
                'Whatsapp verify_token does not match match',
                [
                    'remote-token' => request()->hub_verify_token,
                    'local-token' => config('whatsapp.webhook_verify')
                ]
            );

            return response()->json(['error' => 'Invalid verify token'], 403);
        }

        Log::info('Whatsapp verify_token match');

        return response(request()->hub_challenge);
    }
}
