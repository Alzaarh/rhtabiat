<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'max' => 'integer|min:1',
            'min' => 'integer|min:1',
            'percent' => 'required_without:value|between:1, 100',
            'value' => 'integer|min:1',
            'users' => 'array',
            'users.*' => 'required|exists:users,id',
            'expires_at' => 'required|date_format:Y-m-d',
            'count' => 'required_without:users|integer|min:1',
            'code' => 'string',
        ];
    }
}
