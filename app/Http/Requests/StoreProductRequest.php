<?php

namespace App\Http\Requests;

use App\Models\ProductItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255|unique:products',
            'category_id' => 'required|exists:product_categories,id',
            'short_desc' => 'required|max:2000',
            'desc' => 'required',
            'image' => 'required|image|max:5120',
            'meta_tags' => 'string',
            'off' => 'integer|between:0,99',
            'has_container' => 'required|boolean',
            'items' => 'required|array',
            'items.*.id' => [
                function ($attribute, $value, $fail) {
                    if (request()->has('product') && ProductItem::where('id', $value)->where('product_id', request()->product->id)->doesntExist()) {
                        $fail('Invalid ' . $attribute);
                    }
                },
            ],
            'items.*.weight' => 'required|numeric',
            'items.*.price' => 'required|integer|min:0',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.container' => [
                'required_if:has_container,1',
                'prohibited_if:has_container,0',
                Rule::in([ProductItem::ZINC_CONTAINER, ProductItem::PLASTIC_CONTAINER]),
            ],
        ];
    }
}
