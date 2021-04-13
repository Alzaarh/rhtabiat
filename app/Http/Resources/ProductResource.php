<?php

namespace App\Http\Resources;

use App\Models\ProductItem;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image,
            'short_desc' => $this->short_desc,
            'price' => $this->when(filled($this->price), $this->price),
            'min_price' => $this->when(
                filled($this->min_price),
                $this->min_price
            ),
            'max_price' => $this->when(
                filled($this->max_price),
                $this->max_price
            ),
            'off' => $this->off,
            'avg_score' => $this->avg_score,
            'category' => $this->category,
            $this->mergeWhen(Route::currentRouteName() === 'products.show', function () {
                return [
                    'desc' => $this->desc,
                    'meta_tags' => $this->meta_tags,
                    'items' => [
                        $this->mergeWhen($this->hasContainer(), function () {
                            return [
                                'zink' => ProductItemResource::collection(
                                    $this->items->where('container', ProductItem::ZINK_CONTAINER)
                                ),
                                'plastic' => ProductItemResource::collection(
                                    $this->items->where('container', ProductItem::PLASTIC_CONTAINER)
                                ),
                            ];
                        }),
                        $this->mergeWhen(!$this->hasContainer(), function () {
                            return ProductItemResource::collection($this->items);
                        }),
                    ],
                ];
            }),
        ];
    }
}
