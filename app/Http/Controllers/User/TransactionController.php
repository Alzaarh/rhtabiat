<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(StoreTransactionRequest $request)
    {
        $order = Order::whereCode($request->order_code)->first();

        $result = $this->transactionService->initiateWithZarinpal(
            $order->total_price
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

    public function verify()
    {
        $authority = request()->query('Authority');
        $transaction = Transaction::where('authority', $authority)->first();
        abort_if(empty($transaction), 400);
        $data = [
            'MerchantID' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'Authority' => $authority,
            'Amount' => $transaction->order->total_price,
        ];

        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: '.strlen($jsonData)
            )
        );

        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);

        if ($result['Status'] == 101) {
            $transaction->ref_id = $result['RefID'];
            $transaction->order->status = Order::STATUS_LIST['being_processed'];
            DB::transaction(function () use ($transaction) {
                $transaction->save();
                $transaction->order->save();
            });
            return response()->json(['message' => 'Transaction verified']);
        }
        return response()->json(['message' => 'Transaction failed'], 400);
    }
}
