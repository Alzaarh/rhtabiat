<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
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
            'image_id' => 'required|exists:images,id',
            'location' => ['required', Rule::in(Banner::LOCATIONS)]
        ]);
        Banner::create([
            'image_id' => request()->image_id,
            'location' => request()->location,
        ]);
        return response()->json(['message' => 'بنر با موفقیت ایجاد شد'], 201);
    }

    public function update(Banner $banner)
    {
        request()->validate([
            'image_id' => 'required|exists:images,id',
            'location' => ['required', Rule::in(Banner::LOCATIONS)],
        ]);
        $banner->image_id = request()->image_id;
        $banner->location = request()->location;
        $banner->save();
        return response()->json(['message' => 'بنر با موفقیت به روز رسانی شد']);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return response()->json(['message' => 'بنر با موفقیت حذف شد']);
    }
}
