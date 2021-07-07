<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Log;

class LogController extends Controller
{
    public function store()
    {
        request()->validate(['referrer' => 'required|string|max:255']);

        Log::create([
            'referrer' => request()->referrer,
            'visitor' => request()->ip(),
        ]);

        return response('');
    }
}
