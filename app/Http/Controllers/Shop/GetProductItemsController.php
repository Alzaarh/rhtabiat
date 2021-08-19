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
        $request->validate(['products' => 'required|array']);

        $ids = implode(',', $request->query('products'));

        return ProductItemResource::collection(
            ProductItem::with('product.image')->whereIn('id', request()->products)
                ->orderByRaw(DB::raw("FIELD(id, $ids)"))
                ->get()
        );
    }
}
