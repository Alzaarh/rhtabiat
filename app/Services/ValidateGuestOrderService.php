<?php


namespace App\Services;


use App\Models\ProductItem;
use Illuminate\Validation\ValidationException;

class ValidateGuestOrderService
{
    public function handle(array $items): array
    {
        $orderItems = [];

        foreach ($items as $item) {
            $productItem = ProductItem::find($item['id']);
            if ($productItem->quantity < $item['quantity']) {
                throw ValidationException::withMessages([
                    'products' => [
                        'تعداد محصول انتخاب شده بیش از حد مجاز است',
                    ],
                ]);
            }

            $orderItems[] = [
                'product_id' => $productItem->product->id,
                'product_item_id' => $productItem->id,
                'quantity' => $item['quantity'],
                'off' => $productItem->product->off,
                'weight' => $productItem->weight,
                'price' => $productItem->price,
            ];
        }
        
        return $orderItems;
    }
}
