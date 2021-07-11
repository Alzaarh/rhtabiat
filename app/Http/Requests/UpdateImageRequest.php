<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'alt' => 'string|max:255',
            'title' => 'required|string|max:255',
            'short_desc' => 'string|max:1000',
            'desc' => 'string|max:10000',
//            'url' => [
//                'string',
//                'max:255',
//                Rule::unique('images')->ignore($this->image),
//            ],
            'image' => 'image|max:10240',
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
