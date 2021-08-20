<?php

namespace App\Http\Controllers\Shop;

use App\Http\Resources\ProductItemResource;
use App\Models\ProductItem;
use DB;
use Illuminate\Http\Request;

class GetProductItemsController
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'required|exists:product_items,id',
        ]);

        $ids = implode(',', $request->query('products'));
        return ProductItemResource::collection(
            ProductItem::with('product')->whereIn('id', $request->query('products'))
                ->orderByRaw(DB::raw("FIELD(id, $ids)"))
                ->get()
        );
    }
}
