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
        if (request()->has('address_id') && !auth('user')->check()) {
            return false;
        }
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
                    if (Address::where('id', $value)
                        ->where('user_id', auth('user')->user()->id)
                        ->doesntExist()) {
                        $fail('Invalid' . $attribute);
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
            'items' => 'required_without:cart_id|array',
            'items.*.id' => 'required|exists:product_items,id',
            'items.*.quantity' => 'required|integer',
            'payment_method' => ['required', Rule::in(Order::PAYMENT_METHODS)],
        ];
    }
}
