<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;

class MessageController
{
    // public function __construct()
    // {
    //     $this->middleware();
    // }

    public function index()
    {
        return response()->json(['data' => Message::paginate(10)]);
    }

    public function show(Message $message)
    {
        return response()->json(['data' => $message]);
    }

    public function store(StoreMessageRequest $request)
    {
        Message::create($request->validated());

        return response()->json([
            'message' => 'پیام با موفقیت ارسال شد',
        ], 201);
    }
}
