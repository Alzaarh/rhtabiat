<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function getSelf()
    {
        return new AdminResource(request()->user());
    }

    public function store(Request $request)
    {
        $request->validate([
            "username" => [
                "required",
                "unique:admins",
            ],
            "password" => ["required"],
            "role" => [
                "required",
                Rule::in(Admin::ROLES)
            ],
        ]);
        $admin = new Admin;
        $admin->username = $request->input("username");
        $admin->password = $request->input("password");
        $admin->role = $request->input("role");
        if ($request->input("role") === Admin::ROLES["discount_generator"]) {
            $admin->social_token = Str::random(40) . Admin::count();
        }
        $admin->save();
        return response()->json(["message" => "ادمین با موفقیت اضافه شد"], 201);
    }

    public function index()
    {
        return AdminResource::collection(Admin::all());
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            "username" => [
                "required",
                Rule::unique("admins")->ignore($admin),
            ],
            "password" => ["required"],
            "role" => [
                "required",
                Rule::in(Admin::ROLES)
            ],
        ]);
        $admin->update($request->all());
        return response()->json(["message" => "ادمین با موفقیت به روزرسانی شد"]);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json(["message" => "ادمین با موفقیت حذف شد"]);
    }
}
