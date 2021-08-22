<?php

namespace App\Http\Requests;

use App\Models\Admin;
use App\Models\ArticleCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:articles',
            ],
            'short_desc' => [
                'required',
                'string',
                'max:1000',
            ],
            'image_id' => [
                'exists:images,id',
            ],
            'body' => [
                'required',
                'string',
            ],
            'meta' => [
                'json',
            ],
            'article_category_id' => [
                'required',
                'exists:article_categories,id',
            ],
            'is_verified' => 'boolean',
            'is_waiting' => 'boolean',
        ];
    }

    public function admin(): Admin
    {
        return $this->user();
    }
}
