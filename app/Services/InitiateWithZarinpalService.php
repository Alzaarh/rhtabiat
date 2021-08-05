<?php


namespace App\Services;


class InitiateWithZarinpalService
{
    public function handle(int $amount, string $userEmail, string $userMobile): array
    {
        $jsonData = json_encode(
            [
                'merchant_id' => config('app.zarinpal.key'),
                'amount' => $amount * 10,
                'callback_url' => config('app.zarinpal.callback_url'),
                'description' => 'خرید از فروشگاه '.config('app.name'),
                'metadata' => ['email' => $userEmail, 'mobile' => $userMobile],
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
