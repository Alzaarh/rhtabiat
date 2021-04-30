<?php


namespace App\Services;


use App\Models\ProductItem;
use Illuminate\Validation\ValidationException;

class ValidateGuestOrderService
{
    public function handle(array $items): array
    {
        $orderItems = [];
        $productItems = ProductItem::with('product')
            ->find(array_column($items, 'id'));
        $productItems->each(
            function ($productItem, $index) use (&$orderItems, $items) {
                $quantity = $items[$index]['quantity'];
                echo $quantity;
                if ($productItem->quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'products' => [
                            'تعداد محصول انتخاب شده بیش از حد مجاز است',
                        ],
                    ]);
                }
                $orderItems[] = [
                    'product_id' => $productItem->product->id,
                    'product_item_id' => $productItem->id,
                    'quantity' => $quantity,
                    'off' => $productItem->product->off,
                    'weight' => $productItem->weight,
                    'price' => $productItem->price,
                ];
            });
        return $orderItems;
    }
}
