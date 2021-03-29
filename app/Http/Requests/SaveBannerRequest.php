<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SaveBannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'string|max:1000',
            'image' => 'required|file|image|max:5120',
            'link_text' => 'string|max:255',
            'link_dest' => 'string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->isActive,
            'link_text' => $this->linkText,
            'link_dest' => $this->linkDest,
        ]);
    }
}
