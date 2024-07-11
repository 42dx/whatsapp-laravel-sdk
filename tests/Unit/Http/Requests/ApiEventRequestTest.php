<?php

namespace The42dx\Whatsapp\Tests\Unit\Http\Requests;

use The42dx\Whatsapp\Http\Requests\ApiEventRequest;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ApiEventRequestTest extends UnitTestCase {
    public function test__rules__returns_expected_rules() {
        $request = new ApiEventRequest();
        $rules   = $request->rules();

        $this->assertEquals([
            'entry'           => 'required|array|min:1',
            'entry.*.changes' => 'required|array|min:1',
        ], $rules);
    }
}
