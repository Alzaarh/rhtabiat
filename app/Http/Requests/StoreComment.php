<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreComment extends FormRequest
{
    public function rules()
    {
        return [
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'body' => 'required|string|max:2000',
            'score' => 'required|integer|between:0,5',
            'resource_type' => 'required|in:Article,Product',
            'resource_id' => [
                'required',
                function ($attr, $value) {
                    $resource = null;
                    if ($this->resource_type === 'Article') {
                        $resource = Article::findorFail($value);
                    } elseif ($this->resource_type === 'Product') {
                        $resource = Product::findOrFail($value);
                    }
                    $this->merge(['resource' => $resource]);
                },
            ],
        ];
    }

    public function attributes()
    {
        return [
            'author_name' => 'نام',
            'author_email' => 'ایمیل',
            'score' => 'امتیاز',
            'body' => 'نظر',
        ];
    }
}
