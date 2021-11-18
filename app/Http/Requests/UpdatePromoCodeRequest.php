<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_only' => 'boolean',
            'one_per_user' => 'boolean',
            'off_percent' => 'integer|min:1|max:99',
            'off_value' => 'integer|min:1000',
            'max' => 'integer|min:1000',
            'min' => 'integer|min:1000',
            'infinite' => 'boolean',
            'count' => 'integer|min:1',
            'valid_days' => 'integer|min:1',
        ];
    }
}
