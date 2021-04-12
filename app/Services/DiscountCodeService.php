<?php

namespace App\Services;

use App\Models\DiscountCode;
use Illuminate\Support\Facades\DB;

class DiscountCodeService
{
    public function handleNewBatch(array $data) : void
    {
        empty($data['users'])
            ? $this->createForGuests($data)
            : $this->createForUsers($data);
    }

    private function createForGuests(array $data) : void
    {
        $codes = [];

        for ($i = 0; $i < $data['count']; $i++) {
            array_push($codes, [
                'expires_at' => $data['expires_at'],
                'min' => $data['min'],
                'max' => $data['max'],
                'percent' => $data['percent'],
                'value' => $data['value'],
                'code' => DiscountCode::generateCode(),
            ]);
        }

        DB::table('discount_codes')->insert($codes);
    }

    private function createForUsers(array $data) : void
    {
        $codes = [];

        foreach ($data['users'] as $user) {
            array_push($codes, [
                'user_id' => $user,
                'expires_at' => $data['expires_at'],
                'min' => $data['min'],
                'max' => $data['max'],
                'percent' => $data['percent'],
                'value' => $data['value'],
                'code' => DiscountCode::generateCode(),
            ]);
        }

        DB::table('discount_codes')->insert($codes);
    }
}
