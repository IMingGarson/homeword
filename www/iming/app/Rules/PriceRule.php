<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Constants\Price;

class PriceRule implements Rule
{
    protected $currency;
    public function __construct($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
    }

    public function passes($attribute, $value)
    {
        if ($this->currency == 'USD')
        {
            return $value * Price::USD_EXCHANGE_RATE <= Price::NTD_PRICE_LIMIT;
        }

        return $value <= Price::NTD_PRICE_LIMIT;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Price Error.';
    }
}
