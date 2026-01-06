<?php

namespace The42dx\Whatsapp\Tests\Unit\Http\Requests;

use Illuminate\Validation\Rule;
use The42dx\Whatsapp\Enums\ObjectType;
use The42dx\Whatsapp\Http\Requests\ApiEventRequest;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class ApiEventRequestTest extends UnitTestCase {
    private ApiEventRequest $request;

    protected function setUp(): void {
        parent::setUp();

        $this->request = new ApiEventRequest;
    }

    public function test__rules__it_should_return_the_expected_rules(): void {
        $this->assertEquals([
            'object' => Rule::enum(ObjectType::class),
            'entry' => 'required|array|min:1',
            'entry.*.changes' => 'required|array|min:1',
        ], $this->request->rules());
    }

    public function test__messages__it_should_return_expected_custom_error_messages(): void {
        $this->assertEquals([
            'object' => 'The value of the \'object\' field is not supported or is invalid.',
        ], $this->request->messages());
    }
}
