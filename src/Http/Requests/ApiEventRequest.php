<?php

namespace The42dx\Whatsapp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ApiEventRequest
 *
 * Request object for the Whatsapp Business API webhook handler.
 */
class ApiEventRequest extends FormRequest {
    /**
     * entry
     *
     * The entry object sent by the Whatsapp Business API
     *
     * @var array
     */
    public array $entry;

    /**
     * The rules that the request must pass.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'entry'           => 'required|array|min:1',
            'entry.*.changes' => 'required|array|min:1',
        ];
    }
}
