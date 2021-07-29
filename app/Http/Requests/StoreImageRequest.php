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
            'url' => 'string|max:100|starts_with:images/|ends_with:.png,.jpg,.jpeg|unique:images',
            'image' => 'required|image|max:10240',
            'group' => 'in:1,2,3',
        ];
    }

    public function attributes()
    {
        return [
            'alt' => 'متن جایگزین',
            'title' => 'عنوان',
            'short_desc' => 'توضیح کوتاه',
            'desc' => 'توضیح',
            'url' => 'لینک پیوست',
            'image' => 'فایل پیوست',
            'group' => 'دسته بندی',
        ];
    }
}
