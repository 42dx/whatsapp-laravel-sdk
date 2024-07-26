<?php

namespace The42dx\Whatsapp\Http\Requests;

use Illuminate\Validation\Rule;
use The42dx\Whatsapp\Abstracts\FormRequest;
use The42dx\Whatsapp\Enums\ObjectType;

/**
 * ApiEventRequest
 *
 * Request object for the Whatsapp Business API webhook handler.
 */
class ApiEventRequest extends FormRequest {
    /**
     * The rules that the request must pass.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'object'          => Rule::enum(ObjectType::class),
            'entry'           => 'required|array|min:1',
            'entry.*.changes' => 'required|array|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'object' => 'The value of the \'object\' field is not supported or is invalid.',
        ];
    }
}
