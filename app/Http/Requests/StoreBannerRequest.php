<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Banner;

class StoreBannerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image_id' => 'required|exists:images,id',
            'location' => ['required', Rule::in(Banner::LOCATIONS)],
            'link' => 'string|max:255|url',
        ];
    }

    public function attributes()
    {
        return Banner::VALIDATION_NAMES;
    }
}
