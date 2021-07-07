<?php

namespace App\Services;

class TransactionService
{
    public function initiateWithZarinpal(int $amount)
    {
        $data = [
            'MerchantID' => config('app.zarinpal_key'),
            'Amount' => $amount,
            'CallbackURL' => route('transactions.verify'),
            'Description' => 'خرید از فروشگاه '.config('app.name'),
        ];

        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: '.strlen($jsonData),
        ]);
        $result = curl_exec($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);

        return $result;
    }

    public function verifyWithZarinpal(string $authority, int $amount): array
    {
        $data = [
            'MerchantID' => config('app.zarinpal_key'),
            'Authority' => $authority,
            'Amount' => $amount,
        ];

        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: '.strlen($jsonData),
        ]);

        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
}
