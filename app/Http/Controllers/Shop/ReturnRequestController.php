<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturnRequestRequest;
use App\Http\Resources\ReturnRequestResource;
use App\Models\ReturnRequest;
use App\Models\Order;

class ReturnRequestController extends Controller
{
    public function index()
    {
        return ReturnRequestResource::collection(ReturnRequest::paginate(10));
    }

    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load('order.items', 'order.guestDetail');

        return response()->json(['data' => $returnRequest]);
    }

    public function store(StoreReturnRequestRequest $request)
    {
        Order::whereCode($request->input('order_code'))
            ->first()
            ->returnRequests()
            ->save(new ReturnRequest($request->validated()));

        return response()->json([
            'statusCode' => '201',
            'message' => __('messages.returnRequest.store'),
        ], 201);
    }
}
