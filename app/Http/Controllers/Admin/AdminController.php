<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;

class AdminController extends Controller
{
    public function getSelf()
    {
        return new AdminResource(request()->user());
    }
}
