<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'alt' => 'string|max:255',
            'title' => 'required|string|max:255',
            'short_desc' => 'string|max:1000',
            'desc' => 'string|max:10000',
            'url' => 'required|string|max:255|unique:images',
            'image' => 'required|image|max:5120',
            'group' => 'in:1,2,3',
        ];
    }

    public function attributes()
    {
        return [
            'url' => 'لینک پیوست',
        ];
    }
}
