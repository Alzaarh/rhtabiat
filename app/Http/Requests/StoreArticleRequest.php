<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    public function authorize()
    {
        if (filled($this->article) && $this->user()->id !== $this->article->admin_id) {
            return false;
        }
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'max:255',
                Rule::unique('articles')->ignore($this->article),
            ],
            'short_desc' => 'required|string|max:1000',
            'image_id' => 'nullable|exists:images,id',
            'body' => 'required',
            'meta' => 'nullable|json',
            'article_category_id' => 'required|exists:article_categories,id',
        ];
    }
}
