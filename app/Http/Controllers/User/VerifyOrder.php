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
        if ($transaction->order->getGuestDetail()) {
            $phone = $transaction->order->getGuestDetail()->mobile;
            $name = $transaction->order->getGuestDetail()->name;
        } else {
            $phone = $transaction->order->address->mobile;
            $name = $transaction->order->address->name;
        }
        $result = $verifyZarinpal->handle($request->authority, $transaction->amount);
        // if (empty($result['errors']) && $result['data']['code'] == 100) {
        //     DB::transaction(function () use ($transaction, $result) {
        //         $transaction->verify($result['data']['ref_id']);
        //         $transaction->order->verify();
        //     });

        //     NotifyViaSms::dispatch(
        //         $phone,
        //         config('app.sms_patterns.order_verified'),
        //         [
        //             'name' => $name,
        //             'url' => config('app.track_url'),
        //             'code' => $transaction->order->code,
        //         ]
        //     );
        //     return response()->json([
        //         'message' => 'تراکنش با موفقیت انجام شد',
        //         'data' => [
        //             'code' => 1,
        //         ],
        //     ]);
        // }
        if (empty($result['errors'])) {
            DB::transaction(function () use ($transaction, $result) {
                $transaction->verify($result['RefID']);
                $transaction->order->verify();
            });

            NotifyViaSms::dispatchSync(
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

        NotifyViaSms::dispatchSync(
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
