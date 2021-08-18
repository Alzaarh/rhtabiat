<?php

namespace App\Http\Controllers\Shop;

use App\Http\Resources\ProductItemResource;
use App\Models\ProductItem;
use DB;

class GetProductItemsController
{
    public function __invoke()
    {
        request()->validate([
            'products' => 'required|array',
            'products.*' => 'required|exists:product_items,id',
        ]);

        $idArray = implode(',', request()->products);
        return ProductItemResource::collection(
            ProductItem::with('product')
                ->whereIn('id', request()->products)
                ->orderByRaw(DB::raw("FIELD(id, $idArray)"))
                ->get()
        );
    }
}
