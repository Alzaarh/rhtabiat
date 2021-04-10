<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'max' => 'integer|min:1',
            'min' => 'integer|min:1',
            'percent' => 'required_without:value|between:1, 100',
            'value' => 'integer|min:1',
        ];
    }
}
