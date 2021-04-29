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
        $result = $verifyZarinpal->handle($request->authority, $transaction->amount);

        if ($result['Status'] == 100) {
            DB::transaction(function () use ($transaction, $result) {
                $transaction->verify($result['RefID']);
                $transaction->order->verify();
            });

            $phone = null;
            if ($transaction->order->guestDetail) {
                $phone = $transaction->order->guestDetail->mobile;
            }
            NotifyViaSms::dispatch(
                $phone,
                config('app.sms_patterns.order_verified'),
                [
                    'name' => $transaction->order->guestDetail->name,
                    'url' => config('app.track_url'),
                    'code' => $transaction->order->code,
                ]
            );
            return response()->json(['message' => 'تراکنش با موفقیت انجام شد']);
        }

        $transaction->reject();
        return response()->json(['message' => 'تراکنش با موفقیت انجام نشد']);
    }
}
