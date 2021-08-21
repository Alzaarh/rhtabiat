<?php

namespace App\Rules;

use App\Models\PromoCode;
use Illuminate\Contracts\Validation\Rule;

class CheckMinPromoCode implements Rule
{
    private int $orderCost;

    private PromoCode $promoCode;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $orderCost)
    {
        $this->orderCost = $orderCost;
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
        $this->promoCode = PromoCode::whereCode($value)->first();
        return $this->promoCode->min <= $this->orderCost;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('errors.promo_code_min', ['min' => $this->promoCode->min]);
    }
}
