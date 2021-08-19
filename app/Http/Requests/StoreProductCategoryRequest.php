<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'image_id' => 'required|exists:images,id',
            'image_mobile_id' => 'required|exists:images,id',
            'parent_id' => 'nullable|exists:product_categories,id',
        ];
    }
}
