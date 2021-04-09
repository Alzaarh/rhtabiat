<?php

namespace App\Http\Requests;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_id' => [
                function ($attribute, $value, $fail) {
                    if (!auth('user')->check()) {
                        $fail("You can not use {$attribute} right now.");
                    } else {
                        if (
                            Address::where('id', $value)
                                ->where('user_id', auth('user')->user()->id)
                                ->doesntExist()
                        ) {
                            $fail($attribute . ' does not exist.');
                        }
                    }
                },
            ],
            'name' => 'required_without:address_id|max:255',
            'company' => 'max:255',
            'mobile' => 'required_without:address_id|digits:11',
            'phone' => 'digits:11',
            'state' => 'required_without:address_id|max:255',
            'city' => 'required_without:address_id|max:255',
            'zipcode' => 'required_without:address_id|digits:10',
            'address' => 'required_without:address_id|max:2000',
            'products' => 'required_without:address_id|array',
            'products.*.id' => 'required|exists:product_items,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
