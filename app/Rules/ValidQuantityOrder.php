<?php

namespace App\Rules;

use App\Models\ProductItem;
use Illuminate\Contracts\Validation\Rule;

class ValidQuantityOrder implements Rule
{
    private array $purchasedItems;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $purchasedItems)
    {
        $this->purchasedItems = $purchasedItems;
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
        $productItem = ProductItem::find($value);
        foreach ($this->purchasedItems as $purchasedItem) {
            if (
                $purchasedItem['id'] === $productItem->id &&
                $purchasedItem['quantity'] > $productItem->quantity
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('errors.order_invalid_quantity');
    }
}
