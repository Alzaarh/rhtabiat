<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'count' => 'integer|min:1|max:15',
            'sortBy' => 'in:lowPrice,highPrice',
            'minPrice' => 'integer|min:1',
            'maxPrice' => 'integer|min:1|gt:minPrice'
        ];
    }
}
