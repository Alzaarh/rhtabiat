<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Province;

class IndexProvinces extends Controller
{
    public function __invoke()
    {
        return response()->json(['data' => Province::all()]);
    }
}
