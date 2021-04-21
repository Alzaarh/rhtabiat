<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request)
    {
        Message::create($request->validated());
        return response()->json([
            'message' => 'پیام با موفقیت ارسال شد',
        ], 201);
    }
}