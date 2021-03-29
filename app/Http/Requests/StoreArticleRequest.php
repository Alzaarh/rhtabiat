<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Article;

class StoreArticleRequest extends FormRequest
{
    public function authorize()
    {
        $article = $this->route('article');
        if ($article && $article->admin_id !== auth('admin')->user()->id) {
            return false;
        }
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'thumbnail' => 'file|image|max:5120',
            'body' => 'required|string|max:64000',
            'meta' => 'json',
            'categoryId' => 'required|exists:blog_categories,id',
        ];
    }
}
