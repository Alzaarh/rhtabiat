<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class IndexBannerLocation extends Controller
{
    public function __invoke()
    {
        return response()->json(['data' => Banner::LOCATIONS]);
    }
}
