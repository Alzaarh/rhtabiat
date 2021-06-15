<?php

namespace App\Http\Requests;

use App\Models\ProductItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('products')->ignore($this->product),
            ],
            'category_id' => 'required|exists:product_categories,id',
            'short_desc' => 'required|max:2000',
            'desc' => 'required',
            'image_id' => 'required|exists:images,id',
            'meta_tags' => 'string',
            'off' => 'integer|between:0,99',
            'has_container' => 'required|boolean',
            'items' => 'required|array',
            'items.*.weight' => 'required|numeric',
            'items.*.price' => 'required|integer|min:0',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.container' => [
                'required_if:has_container,1',
                'prohibited_if:has_container,0',
                Rule::in([ProductItem::ZINC_CONTAINER, ProductItem::PLASTIC_CONTAINER]),
            ],
            'is_best_selling' => 'boolean',
        ];
    }
}
