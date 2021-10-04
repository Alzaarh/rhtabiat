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
                'required',
                'exists:product_items,id',
            ],
            'product_items.*.quantity' => [
                "required",
                "integer",
                "min:1",
            ],
        ];
    }
}
