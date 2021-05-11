<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'image' => 'required|image|max:5120',
            'image_mobile' => 'required|image|max:5120',
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
