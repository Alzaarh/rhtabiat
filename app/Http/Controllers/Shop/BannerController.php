<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    public function index()
    {
        return BannerResource::collection(Banner::all());
    }

    public function store()
    {
        request()->validate([
            'image' => 'required|image|max:5120',
            'location' => ['required', Rule::in(Banner::LOCATIONS)]
        ]);
        $image = request()->image->store('images');
        Banner::create([
            'image' => $image,
            'location' => request()->location,
        ]);
        return response()->json(['message' => 'بنر با موفقیت ایجاد شد'], 201);
    }

    public function update(Banner $banner)
    {
        request()->validate([
            'image' => 'image|max:5120',
            'location' => ['required', Rule::in(Banner::LOCATIONS)],
        ]);
        if (request()->hasFile('image')) {
            Storage::delete($banner->image);
            $banner->image = request()->image->store('images');
        }
        $banner->location = request()->location;
        $banner->save();
        return response()->json(['message' => 'بنر با موفقیت به روز رسانی شد']);
    }

    public function destroy(Banner $banner)
    {
        Storage::delete($banner->image);
        $banner->delete();
        return response()->json(['message' => 'بنر با موفقیت حذف شد']);
    }
}
