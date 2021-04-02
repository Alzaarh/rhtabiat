<?php

namespace App\Http\Resources;

use App\Models\ProductItem;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'min_price' => ProductItem::orderBy('price', 'asc')->value('price'),
                'max_price' => ProductItem::orderBy('price', 'desc')->value('price'),
                'search_found_count' => $request->product_search_found_count,
            ]
        ];
    }
}
