<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'image_id' => 'required|exists:images,id',
            'image_mobile_id' => 'required|exists:images,id',
            'parent_id' => 'nullable|exists:product_categories,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'نام',
            'image_id' => 'عکس',
            'image_mobile_id' => 'عکس حالت موبایل',
            'parent_id' => 'والد',
        ];
    }
}
