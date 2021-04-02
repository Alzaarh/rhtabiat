<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'author_name' => 'required|max:255',
            'author_email' => 'required|email|max:255',
            'body' => 'required|max:2000',
            'score' => 'required|integer|between:0,5',
            'resource_type' => 'required|in:Article,Product',
            'resource_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($this->resource_type === 'Article') {
                        $article = Article::find($value);
                        if (empty($article)) {
                            $fail('Invalid' . $attribute);
                        }
                        $this->merge(['resource' => $article]);
                    }
                    if ($this->resource_type === 'Product') {
                        $product = Product::find($value);
                        if (empty($product)) {
                            $fail('Invalid' . $attribute);
                        }
                        $this->merge(['resource' => $product]);
                    }
                }
            ],
        ];
    }
}
