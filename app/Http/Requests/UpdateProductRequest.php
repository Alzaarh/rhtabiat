<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'slug' => [
                'string',
                'max:255',
                Rule::unique('products')->ignore($this->product),
            ],
            'category_id' => 'exists:product_categories,id',
            'short_desc' => 'string|max:2000',
            'desc' => 'string',
            'image_id' => 'exists:images,id',
            'meta_tags' => 'nullable|string',
            'off' => 'integer|between:0,99',
            'has_container' => 'required|boolean',
            'items' => 'required|array',
            'items.*.weight' => 'required|numeric',
            'items.*.price' => 'required_without:price|integer|min:0',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.container' => [
                'required_if:has_container,1',
                'prohibited_if:has_container,0',
                Rule::in([ProductItem::ZINC_CONTAINER, ProductItem::PLASTIC_CONTAINER]),
            ],
            'is_best_selling' => 'boolean',
            'package_price' => 'integer|min:0',
            'unit' => Rule::in(Product::UNITS),
            'is_disabled' => 'boolean',
        ];
    }
}
