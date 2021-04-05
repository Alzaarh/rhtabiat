<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
            'image' => 'nullable|image|max:5120',
            'body' => 'required',
            'meta' => 'nullable|json',
            'article_category_id' => 'required|exists:article_categories,id',
        ];
    }
}
