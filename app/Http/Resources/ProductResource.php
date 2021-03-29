<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'price' => $this->when(filled($this->price), $this->price),
            'minPrice' => $this->when(
                filled($this->min_price), $this->min_price
            ),
            'maxPrice' => $this->when(
                filled($this->max_price), $this->max_price
            ),            $this->mergeWhen($request->has('withDetails'), function () {
                return [
                    'shortDesc' => $this->short_desc,
                    'desc' => $this->desc,
                    'containers' => $this->when(
                        $this->hasContainer(), function () {
                            return [
                                'zink' => $this->zinkContainerFeatures,
                                'plastic' => $this->plasticContainerFeatures,
                            ];
                        }
                    ),
                    'features' => $this->when(
                        !$this->hasContainer(), function () {
                            return $this->features;
                        }
                    ),
                    'category' => new CategoryResource($this->category),
                ];
            }),
            'off' => $this->when(filled($this->off), $this->off),
        ];
    }
}
