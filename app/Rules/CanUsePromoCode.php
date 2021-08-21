<?php

namespace App\Rules;

use App\Models\PromoCode;
use Illuminate\Contracts\Validation\Rule;

class CanUsePromoCode implements Rule
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
        // TODO: when user is implemented change this.
        return !PromoCode::whereCode($value)->first()->user_only;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('errors.promo_code_user_only');
    }
}
