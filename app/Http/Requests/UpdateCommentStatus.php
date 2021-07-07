<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentStatus extends FormRequest
{
    public function rules()
    {
        return [
            'verify' => [
                'required',
                'boolean',
                function ($attr, $value, $fail) {
                    if ($this->comment->status !== Comment::NOT_VERIFIED) {
                        $fail('کامنت تایید یا رد شده است');
                    }
                },
            ],
        ];
    }
}
