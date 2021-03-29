<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'count' => 'integer|min:1|max:15',
            'search' => 'string|max:100',
            'categoryId' => 'integer|min:1',
        ];
    }
}
