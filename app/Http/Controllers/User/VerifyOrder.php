<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOrderRequest;
use App\Jobs\NotifyViaSms;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\IdpayPayment;
use App\Services\VerifyZarinpalService;
use DB;

class VerifyOrder extends Controller
{
    public function __invoke(VerifyOrderRequest $request, VerifyZarinpalService $verifyZarinpal, IdpayPayment $idpayPayment)
    {
        if ($request->input('track_id')) {
            $result = json_decode($idpayPayment->verify(['id' => $request->input('id'), 'order_id' => $request->input('order_id')]));
            $order = Order::find($request->input('order_id'));
            if ($order->address_id) {
                Cart::where('user_id', $order->address->user_id)->update(['is_sms_sent' => false]);
            }
            $transaction = Transaction::where('authority', $request->input('id'))->first();
            $phone = null;
            $name = null;
            if ($order->getGuestDetail()) {
                $phone = $order->getGuestDetail()->mobile;
                $name = $order->getGuestDetail()->name;
            } else {
                $phone = $order->address->mobile;
                $name = $order->address->name;
            }
            if (!property_exists($result, 'error_code') && $result->status == '100') {
                DB::transaction(function () use ($transaction, $result) {
                    $transaction->verify($result->track_id);
                    $transaction->order->verify();
                });
                NotifyViaSms::dispatch(
                    $phone,
                    config('app.sms_patterns.order_verified'),
                    [
                        'name' => $name,
                        'url' => config('app.track_url'),
                        'code' => $transaction->order->code,
                    ]
                );
                return response()->json([
                    'message' => 'تراکنش با موفقیت انجام شد',
                    'data' => [
                        'code' => 1,
                    ],
                    'code' => $result,
                ]);
            } else {
                DB::transaction(function () use ($transaction) {
                    $transaction->reject();
                });

                NotifyViaSms::dispatch(
                    $phone,
                    config('app.sms_patterns.order_rejected'),
                    [
                        'name' => $name,
                        'admin_phone' => '05144452940',
                    ]
                );
                return response()->json([
                    'message' => 'تراکنش با موفقیت انجام نشد',
                    'data' => [
                        'code' => 0,
                    ],
                    'code' => $result,
                ]);
            }
        } else {
            $transaction = $request->transaction;
            $phone = null;
            $name = null;
            if ($transaction->order->getGuestDetail()) {
                $phone = $transaction->order->getGuestDetail()->mobile;
                $name = $transaction->order->getGuestDetail()->name;
            } else {
                $phone = $transaction->order->address->mobile;
                $name = $transaction->order->address->name;
            }
            if ($transaction->order->address_id) {
                Cart::where('user_id', $transaction->order->address->user_id)->update(['is_sms_sent' => false]);
            }
                $result = $verifyZarinpal->handle($request->Authority, $transaction->amount);
                if (empty($result['errors']) && $result['data']['code'] == 100) {
                    DB::transaction(function () use ($transaction, $result) {
                        $transaction->verify($result['data']['ref_id']);
                        $transaction->order->verify();
                    });

                    NotifyViaSms::dispatch(
                        $phone,
                        config('app.sms_patterns.order_verified'),
                        [
                            'name' => $name,
                            'url' => config('app.track_url'),
                            'code' => $transaction->order->code,
                        ]
                    );
                    return response()->json([
                        'message' => 'تراکنش با موفقیت انجام شد',
                        'data' => [
                            'code' => 1,
                        ],
                        'code' => $result,
                    ]);
                }
                DB::transaction(function () use ($transaction) {
                    $transaction->reject();
                });

                NotifyViaSms::dispatch(
                    $phone,
                    config('app.sms_patterns.order_rejected'),
                    [
                        'name' => $name,
                        'admin_phone' => '05144452940',
                    ]
                );
                return response()->json([
                    'message' => 'تراکنش با موفقیت انجام نشد',
                    'data' => [
                        'code' => 0,
                    ],
                    'code' => $result,
                ]);
        }

    }
}
