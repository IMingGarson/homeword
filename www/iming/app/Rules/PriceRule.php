<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PriceRule implements ValidationRule
{
    protected $currency;
    protected $price;
    const UPPER_NTD_BOUND = 2000;

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
        //
    }

    public function passes($attribute, $value)
    {
        $this->price = $value;
        
        if ($this->currency == 'USD')
        {
            return $value * 31 <= $this->UPPER_NTD_BOUND;
        }

        return $value <= $this->UPPER_NTD_BOUND;
    }
}
