<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;

class CreateGuest extends Controller
{
    public function __invoke(Request $request)
    {
        request()->validate(['phone' => 'required']);

        $guest = Guest::create(['phone' => request()->phone]);

        return response()->json(['data' => ['guest_token' => $guest->token]]);
    }
}
