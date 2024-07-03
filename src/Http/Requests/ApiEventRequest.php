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

    /**
     * The error messages the request will return in case of failure.
     *
     * @return array
     */
    public function messages() {
        return [
            'entry.required'           => 'The \'entry\' field is required',
            'entry.array'              => 'The \'entry\' field must be an array',
            'entry.min'                => 'The \'entry\' field must have at least :min items',
            'entry.*.changes.required' => 'The \'entry.changes\' field is required',
            'entry.*.changes.array'    => 'The \'entry.changes\' field must be an array',
            'entry.*.changes.min'      => 'The \'entry.changes\' field must have at least :min items',
        ];
    }
}
