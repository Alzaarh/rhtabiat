<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOrderRequest;
use App\Jobs\NotifyViaSms;
use App\Services\VerifyZarinpalService;
use DB;

class VerifyOrder extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(VerifyOrderRequest $request, VerifyZarinpalService $verifyZarinpal)
    {
        $transaction = $request->transaction;
        $phone = null;
        $name = null;
        if ($transaction->order->forGuest()) {
            $phone = $transaction->order->guestDetail->mobile;
            $name = $transaction->order->guestDetail->name;
        } elseif ($transaction->order->forUser()) {
            $phone = $transaction->order->address->mobile;
            $name = $transaction->order->address->name;
        }
        $result = $verifyZarinpal->handle($request->authority, $transaction->amount);

        if ($result['Status'] == 101) {
            return response()->json([
                'message' => 'تراکنش با موفقیت انجام شد',
                'data' => [
                    'code' => 1,
                ],
            ]);
        }

        if ($result['Status'] == 100) {
            DB::transaction(function () use ($transaction, $result) {
                $transaction->verify($result['RefID']);
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
        ]);
    }
}
