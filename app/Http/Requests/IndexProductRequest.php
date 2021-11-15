<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'count' => 'integer|between:1,30',
            'sort_by' => 'in:lowest_price,highest_price,highest_rated,latest',
            'search' => 'string|between:1,30',
            'best_selling' => 'in:true',
            'featured' => 'in:true',
        ];
    }
}
