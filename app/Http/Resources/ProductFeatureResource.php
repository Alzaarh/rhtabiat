<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductFeatureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            $this->mergeWhen(isset($this->container), function () {
                return [
                    'container' => [
                        $this->container => [
                            'id' => $this->id,
                            'weight' => $this->weight,
                        ],
                    ],
                ];
            }),
        ];
    }
}
