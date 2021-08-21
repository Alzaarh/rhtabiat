<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\PromoCode;
use App\Rules\AvailablePromoCode;
use App\Rules\CanUsePromoCode;
use App\Rules\CheckMinPromoCode;
use App\Rules\ValidPromoCode;
use App\Rules\ValidQuantityOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StoreGuestOrderRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'mobile' => [
                'required',
                'digits:11',
            ],
            'phone' => ['digits:11'],
            'company' => [
                'string',
                'max:255',
            ],
            'province_id' => [
                'required',
                'exists:provinces,id',
            ],
            'city_id' => [
                'required',
                'exists:cities,id',
            ],
            'zipcode' => [
                'required',
                'digits:10',
            ],
            'address' => [
                'required',
                'string',
                'max:1000',
            ],
            'products' => [
                'required',
                'array',
            ],
            'products.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'products.*.id' => [
                'bail',
                'required',
                'distinct',
                'exists:product_items',
                new ValidQuantityOrder($this->products),
            ],
            'promo_code' => [
                'bail',
                'string',
                'exists:promo_codes,code',
                new CanUsePromoCode,
                new ValidPromoCode,
                new CheckMinPromoCode($this->orderCost()),
                new AvailablePromoCode,
            ],
        ];
    }

    public function orderData(): array
    {
        return [
            'delivery_cost' =>
            $this->orderCost() >= Order::FREE_DELIVERY_COST_PRICE
                ? 0
                : $this->deliveryCost(),
            'package_price' => $this->packagePrice(),
            'name' => $this->name,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'company' => $this->company,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'zipcode' => $this->zipcode,
            'address' => $this->address,
            'products' => $this->products,
        ];
    }

    public function promoCode(): ?PromoCode
    {
        return PromoCode::whereCode($this->promo_code)->first();
    }

    protected function prepareForValidation()
    {
        if ($this->promoCode) {
            $this->merge(['promo_code' => $this->promoCode]);
        }
    }

    private function orderCost(): int
    {
        $orderCost = 0;
        foreach ($this->products as $purchasedItem) {
            $item = ProductItem::find($purchasedItem['id']);
            $orderCost += $item->price * $purchasedItem['quantity'];
        }
        return $orderCost;
    }

    private function deliveryCost(): int
    {
        $deliveryCost = 0;
        foreach ($this->products as $purchasedItem) {
            $item = ProductItem::with('product')->find($purchasedItem['id']);
            if ($item->product->unit === Product::KILOGRAM_UNIT) {
                $weight = $item->weight;
                $weight *= $$purchasedItem['quantity'];
                $weight += 0.15;
                $deliveryCost +=
                    $this->province_id === Order::KHORASAN_PROVINCE_ID
                    ? $weight * 9800
                    : $weight * 14000;
                $deliveryCost += 2500;
                $deliveryCost *= 1.1;
            } else {
                $deliveryCost += 20000;
            }
        }
        return $deliveryCost;
    }

    private function packagePrice(): int
    {
        return ProductItem::distinct('product_id')
            ->find(Arr::pluck($this->products, 'id'))
            ->reduce(
                fn ($carry, $product) => $carry + $product->package_price,
                0
            );
    }
}
