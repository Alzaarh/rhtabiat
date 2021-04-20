<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'role:admin'])->except('store');
    }

    public function index()
    {
        return jsonResponse(['data' => Message::paginate()]);
    }

    public function show(Message $message)
    {
        return JsonResponse(['data' => $message]);
    }

    public function store(StoreMessageRequest $request)
    {
        Message::create($request->validated());
        return jsonResponse(['message' => __('messages.success')], 201);
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return jsonResponse(['message' => __('messages.success')]);
    }
}
