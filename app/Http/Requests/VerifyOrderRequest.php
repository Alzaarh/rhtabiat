<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'authority' => [
                'required',
                function ($attr, $value, $fail) {
                    $transaction = Transaction::whereAuthority($value)
                        ->whereStatus(Transaction::STATUS['not_verified'])
                        ->first();
                    if (!$transaction) {
                        return $fail('');
                    }
                    $this->merge(['transaction' => $transaction]);
                },
            ],
        ];
    }
}
