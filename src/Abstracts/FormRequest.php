<?php

namespace The42dx\Whatsapp\Abstracts;

use Illuminate\Foundation\Http\FormRequest as OriginalFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;

/**
 * FormRequest
 *
 * Abstract class containing default methods for form requests.
 */
abstract class FormRequest extends OriginalFormRequest {
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @see \Illuminate\Foundation\Http\FormRequest::failedValidation
     * @see Illuminate\Validation\ValidatesWhenResolvedTrait::failedValidation
     */
    protected function failedValidation(Validator $validator): void {
        Log::debug('Validation Error: ' . json_encode($validator->errors()->all()));

        $exception = $validator->getException();

        throw (new $exception($validator))
                    ->errorBag($this->errorBag);
    }
}
