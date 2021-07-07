<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTransaction implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Order $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'MerchantID' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'Amount' => $this->order->total_price,
            'CallbackURL' => route('transactions.verify'),
            'Description' => config('app.name'),
        ];

        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: '.strlen($jsonData),
            ]
        );
        $result = curl_exec($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);

        if (empty($result['errors']) && $result['Status'] == 100) {
            Transaction::create(
                [
                    'order_id' => $this->order->id,
                    'amount' => $this->order->total_price,
                    'authority' => $result['Authority'],
                ]
            );
        }

        request()->merge(['authority' => $result['Authority']]);
    }
}
