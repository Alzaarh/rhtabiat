<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    public function store()
    {
        request()->validate([
            'image' => 'required|image|max:5120',
            'location' => ['required', Rule::in(Banner::LOCATIONS)],
        ]);

        $path = request()->image->store('images');

        Banner::create([
            'image' => $path,
            'location' => request()->location,
        ]);

        return response()->json(['message' => 'Banner created'], 201);
    }

    public function destroy(Banner $banner)
    {
        Storage::delete($banner->image);

        $banner->delete();

        return response()->json(['message' => 'Banner deleted']);
    }
}
