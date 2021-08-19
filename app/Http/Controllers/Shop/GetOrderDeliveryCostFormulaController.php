<?php

namespace App\Http\Controllers\Shop;

use App\Actions\TranslateProductUnitsAction;
use App\Models\Order;
use App\Models\Product;

class GetOrderDeliveryCostFormulaController
{
    public function __invoke(TranslateProductUnitsAction $action)
    {
        $kilogramUnit = $action->execute()->search(Product::UNITS['kilogram']);
        $withingProvince = Order::WITHIN_PROVINCE;
        $functionBody = "if(u==='$kilogramUnit'){";
        $functionBody .= "if(p===$withingProvince){return(((w+0.15)*9800)+2500)*1.1}";
        $functionBody .= "else{return(((w+0.15)*14000)+2500)*1.1}}";
        $functionBody .= "else{return 20000}";

        $function = [
            'arguments' => 'p,w,u',
            'body' => $functionBody,
        ];

        return response()->json(['data' => $function]);
    }
}
