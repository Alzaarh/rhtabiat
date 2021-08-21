<?php

namespace App\Rules;

use App\Models\PromoCode;
use Illuminate\Contracts\Validation\Rule;

class ValidPromoCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !PromoCode::whereCode($value)->first()->isExpired();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('errors.promo_code_expired');
    }
}
