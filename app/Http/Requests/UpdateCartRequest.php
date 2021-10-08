<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_items' => [
                "required",
                "array",
            ],
            'product_items.*.id' => [
                'exists:product_items,id',
            ],
            'product_items.*.quantity' => [
                "integer",
                "min:1",
            ],
        ];
    }
}
