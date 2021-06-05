<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('product_categories')->ignore($this->category),
            ],
            'image_id' => 'required|exists:images,id',
            'image_mobile_id' => 'required|exists:images,id',
            'parent_id' => 'nullable|exists:product_categories,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'نام',
            'image' => 'عکس',
            'image_mobile' => 'عکس حالت موبایل',
            'parent_id' => 'والد',
        ];
    }
}
