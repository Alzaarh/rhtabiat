<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalcDeliveryCost extends FormRequest
{
    public function rules()
    {
        return [
            'province' => 'required|exists:provinces,id',
            'weight' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0'
        ];
    }

    public function attributes()
    {
        return [
            'province' => 'استان',
            'weight' => 'وزن کل',
            'price' => 'قیمت کل',
        ];
    }
}
