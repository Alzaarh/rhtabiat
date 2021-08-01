<?php


namespace App\Services;


class VerifyZarinpalService
{
    public function handle(string $authority, int $amount): array
    {
        $jsonData = json_encode([
            'merchant_id' => config('app.zarinpal.key'),
            'authority' => $authority,
            'amount' => $amount,
        ]);
        $ch = curl_init(config('app.zarinpal.verify_url'));
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
}
