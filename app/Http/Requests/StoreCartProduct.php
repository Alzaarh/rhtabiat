<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products' => 'required|array',
            'products.*.id' => [
                'required',
                'exists:product_items,id',
                function ($attribute, $value, $fail) {
                    if (
                        request()->user()
                            ->cart
                            ->products()
                            ->where('product_item_id', $value)
                            ->exists()
                    ) {
                        $fail($attribute . ' is invalid');
                    }
                },
            ],
            'products.*.quantity' => 'required|min:1',
        ];
    }
}
