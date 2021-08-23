<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'string',
                'max:255',
            ],
            'slug' => [
                'string',
                'max:255',
                Rule::unique('articles')->ignore($this->articleSlug),
            ],
            'short_desc' => [
                'string',
                'max:1000',
            ],
            'image_id' => [
                'nullable',
                'exists:images,id',
            ],
            'body' => [
                'string',
            ],
            'meta' => [
                'nullable',
                'json',
            ],
            'article_category_id' => [
                'exists:article_categories,id',
            ],
            'is_waiting' => 'boolean',
        ];
    }
}
