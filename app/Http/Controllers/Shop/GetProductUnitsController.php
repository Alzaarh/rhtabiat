<?php

namespace App\Http\Controllers\Shop;

use App\Actions\TranslateProductUnitsAction;

class GetProductUnitsController
{
    public function __invoke(TranslateProductUnitsAction $action)
    {
        return response()->json(['data' => $action->execute()]);
    }
}
