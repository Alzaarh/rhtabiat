<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'slug' => [
                'required',
                'max:255',
                Rule::unique('product_categories')->ignore($this->route('product_category')),
            ],
            'image' => 'required|image|max:5120',
            'parent_id' => 'nullable|exists:product_categories,id',
        ];
    }
}
