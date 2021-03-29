<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class SaveCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'icon' => 'image|max:5120',
            'parent_id' => 'integer|min:1|exists:categories,id',
        ];
        if (Route::currentRouteName() === 'categories.update') {
            $rules['name'] = 'string|max:255';
            $rules['parent_id'] = 'nullable|integer|min:1|exists:categories,id';
        }
        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->whenHas('parentId', function () {
            $this->merge(['parent_id' => $this->parentId]);
        });
    }
}
