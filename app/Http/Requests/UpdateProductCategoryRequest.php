<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'image_id' => 'exists:images,id',
            'image_mobile_id' => 'exists:images,id',
            'parent_id' => 'nullable|exists:product_categories,id',
        ];
    }
}
