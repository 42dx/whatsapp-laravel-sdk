<?php

namespace The42dx\Whatsapp\Tests\Unit\Http\Requests;

use The42dx\Whatsapp\Tests\Unit\UnitTestCase;
use The42dx\Whatsapp\Http\Requests\WebhookCheckRequest;
use The42dx\Whatsapp\Rules\{HubMode, VerifyToken};

class WebhookCheckRequestTest extends UnitTestCase {
    public function test__rules__returns_expected_rules() {
        $request = new WebhookCheckRequest();
        $rules   = $request->rules();

        $this->assertEquals([
            'hub_challenge'    => 'required|string',
            'hub_mode'         => ['required', 'string', new HubMode()],
            'hub_verify_token' => ['required', 'string', new VerifyToken()],
        ], $rules);
    }
}
