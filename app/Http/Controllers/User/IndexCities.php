<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\City;

class IndexCities extends Controller
{
    public function __invoke()
    {
        return response()->json(['data' => City::all()]);
    }
}
