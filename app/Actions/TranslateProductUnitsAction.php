<?php

namespace App\Actions;

use App\Models\Product;

class TranslateProductUnitsAction
{
    public function execute()
    {
        $product = new Product;
        $units = collect();

        collect(Product::UNITS)->each(function ($unitValue) use ($product, $units) {
            $product->unit = $unitValue;

            $units->put($product->getUnitTranslation(), $unitValue);
        });

        return $units;
    }
}
