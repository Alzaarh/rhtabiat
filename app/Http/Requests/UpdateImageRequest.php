<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Image;

class UpdateImageRequest extends FormRequest
{
    public function authorize()
    {
        if (!$this->route('image')->is_server_serve) {
            abort(403);
        }
        return true;
    }

    public function rules()
    {
        return [
            'alt' => 'string|max:255',
            'title' => 'string|max:255',
            'short_desc' => 'string|max:1000',
            'desc' => 'string|max:10000',
            'url' => [
                'string',
                'max:100',
                'starts_with:images/',
                'ends_with:.png,.jpg,.jpeg',
                Rule::unique('images')->ignore($this->image),
            ],
            'image' => 'required_with:url|image|max:10240',
            'group' => 'in:1,2,3',
        ];
    }

    public function attributes()
    {
        return Image::fieldNames();
    }
}
