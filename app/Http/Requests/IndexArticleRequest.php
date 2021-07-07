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
            'count' => 'integer|between:1,15',
            'search' => 'string|max:100',
            'article_category_id' => 'exists:article_categories,id',
        ];
    }
}
