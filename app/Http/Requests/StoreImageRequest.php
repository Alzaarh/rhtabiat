<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Image;

class StoreImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'alt' => 'string|max:255',
            'title' => 'required|string|max:255',
            'short_desc' => 'string|max:1000',
            'desc' => 'string|max:10000',
            'url' => 'string|max:100|starts_with:images/|ends_with:.png,.jpg,.jpeg|unique:images',
            'image' => 'required|image|max:10240',
            'group' => 'in:1,2,3',
        ];
    }

    public function attributes()
    {
        return Image::fieldNames();
    }
}
