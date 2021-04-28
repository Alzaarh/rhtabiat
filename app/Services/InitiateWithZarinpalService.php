<?php


namespace App\Services;


class InitiateWithZarinpalService
{
    public function handle(int $amount): array
    {
        $jsonData = json_encode(
            [
                'MerchantID' => config('app.zarinpal.key'),
                'Amount' => $amount,
                'CallbackURL' => config('app.zarinpal.callback_url'),
                'Description' => 'خرید از فروشگاه '.config('app.name'),
            ]
        );
        $ch = curl_init(config('app.zarinpal.initiate_url'));
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
}
