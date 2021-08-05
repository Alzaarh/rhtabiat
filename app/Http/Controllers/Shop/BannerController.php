<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;

class BannerController extends Controller
{
    public function index()
    {
        return BannerResource::collection(Banner::all());
    }

    public function store(StoreBannerRequest $request)
    {
        Banner::create($request->validated());

        return response()->json(['message' => __('messages.banner.store')], 201);
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $banner->update($request->validated());

        return response()->json(['message' => __('messages.banner.update')]);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return response()->json(['message' => __('messages.banner.destroy')]);
    }
}
