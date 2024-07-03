<?php

namespace The42dx\Whatsapp\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

/**
 * HubMode
 *
 * Validation rule for the hub mode parameter in the Whatsapp webhook check request.
 *
 */
class HubMode implements ValidationRule {
    const HUB_MODE_SUBSCRIBE = 'subscribe';
    const ERROR_INVALID_MODE = 'Invalid hub mode';

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if ($value !== self::HUB_MODE_SUBSCRIBE) {
            Log::error(self::ERROR_INVALID_MODE, [
                'expected' => self::HUB_MODE_SUBSCRIBE,
                'provided' => $value,
            ]);

            $fail(self::ERROR_INVALID_MODE);
        }
    }
}
