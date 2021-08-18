<?php

namespace App\Http\Controllers\Shop;

use App\Actions\TranslateProductUnitsAction;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GetOrderDeliveryCostFormulaController
{
    public function __invoke(Request $request, TranslateProductUnitsAction $action)
    {
        $units = $action->execute();

        $request->validate(['unit' => ['required', Rule::in($units->keys())]]);

        if (Product::UNITS['kilogram'] === $units->get($request->query('unit'))) {
            $withingProvince = Order::WITHIN_PROVINCE;
            $function = [
                'arguments' => 'p,w',
                'body' => "if (p === $withingProvince) {return (((w+0.15)*9800)+2500)*1.1} else {return (((w+0.15)*14000)+2500)*1.1}"
            ];
        } else {
            $function = [
                'arguments' => '',
                'body' => 'return 20000',
            ];
        }

        return response()->json(['data' => $function]);
    }
}
