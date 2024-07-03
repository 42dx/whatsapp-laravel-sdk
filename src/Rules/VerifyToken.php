<?php

namespace The42dx\Whatsapp\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

/**
 * VerifyToken
 *
 * Validation rule for the verify token parameter in the Whatsapp webhook check request.
 *
 */
class VerifyToken implements ValidationRule {
    const ERROR_INVALID_TOKEN = 'Invalid verify token';

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if ($value !== config('whatsapp.webhook_verify')) {
            Log::error(self::ERROR_INVALID_TOKEN, ['providedToken' => $value]);

            $fail(self::ERROR_INVALID_TOKEN);
        }
    }
}
