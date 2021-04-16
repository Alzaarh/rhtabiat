<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\CreateTransaction;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store()
    {
        request()->validate(['order_id' => 'required|exists:orders,id']);

        $order = Order::find(request()->order_id);

        $isNotPaid = $order->status !== Order::STATUS_LIST['not_paid'];
        $hasValidTime = now()->diffInHours($order->created_at) > 24;
        if (auth('user')->check()) {
            abort_if($isNotPaid || $hasValidTime || $order->user_id !== request()->user()->id, 400);
        } else {
            abort_if($isNotPaid || $hasValidTime, 400);
        }

        CreateTransaction::dispatchSync($order);

        return response()->json(['data' => ['authority' => request()->authority]], 201);
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
