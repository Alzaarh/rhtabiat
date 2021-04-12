<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Province;

class ProvinceIndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return response()->json(['data' => Province::all()]);
    }
}