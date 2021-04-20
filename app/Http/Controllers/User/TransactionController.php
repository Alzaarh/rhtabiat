<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Jobs\NotifyViaSms;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\DiscountCodeService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    protected DiscountCodeService $discountCodeService;

    public function __construct(
        TransactionService $transactionService,
        DiscountCodeService $discountCodeService
    ) {
        $this->transactionService = $transactionService;
        $this->discountCodeService = $discountCodeService;
    }

    public function store(StoreTransactionRequest $request)
    {
        $order = Order::whereCode($request->order_code)->first();
        $amount = $order->products_price;
        if (filled($order->discountCode)) {
            $amount = $this->discountCodeService->calcDiscount($order->discountCode, $amount);
        }
        $result = $this->transactionService->initiateWithZarinpal(
            $amount
        );
        if (empty($result['errors']) && $result['Status'] == 100) {
            $order->transactions()->create([
                    'amount' => $order->total_price,
                    'authority' => $result['Authority'],
                ]
            );
        }

        return response()->json([
            'data' => ['authority' => $result['Authority']],
        ], 201);
    }

    /**
     * @throws \Throwable
     */
    public function verify()
    {
        $authority = request()->query('Authority');
        $transaction = Transaction::whereAuthority($authority)->first();
        abort_if(empty($transaction), 400);

        $result = $this->transactionService->verifyWithZarinpal(
            $authority,
            $transaction->amount
        );
        if ($result['Status'] == 100) {
            // Remove time() from production.
            $transaction->ref_id = $result['RefID'].time();
            $transaction->status = Transaction::STATUS['verified'];
            DB::transaction(function () use ($transaction) {
                $transaction->save();
                $transaction->order->verify();
            });

            NotifyViaSms::dispatch(
                $transaction->order->address->mobile,
                config('app.sms_patterns.order_verified'),
                [
                    'name' => $transaction->order->address->name,
                    'url' => 'url',
                    'code' => $transaction->order->code,
                ]
            );

            return response()->json(['message' => 'تراکنش با موفقیت انجام شد']);
        }
        $transaction->status = Transaction::STATUS['rejected'];
        $transaction->save();

        return response()->json([
            'message' => 'تراکنش با موفقیت انجام نشد',
        ], 400);
    }
}
