<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaveBannerRequest;
use App\Models\Banner;
use App\Http\Resources\BannerResource;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'role:admin'])->except('getMain');
    }

    public function index(Request $request)
    {
        return BannerResource::collection(Banner::paginate());
    }

    public function show(Banner $banner)
    {
        return new BannerResource($banner);
    }

    public function store(SaveBannerRequest $request)
    {
        return new BannerResource(
            Banner::create(array_merge($request->validated(), [
                'image' => saveImageOnDisk($request->image),
            ]))
        );
    }

    public function update(SaveBannerRequest $request, Banner $banner)
    {    
        $banner->update(array_merge($request->validated(), [
            'image' => saveImageOnDisk($request->image),
        ]));
        return new BannerResource($banner);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return new BannerResource($banner);
    }

    public function getMain()
    {
        return new BannerResource(
            Banner::isActive()->inRandomOrder()->firstOrFail()
        );
    }
}
