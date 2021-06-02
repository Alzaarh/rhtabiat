<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Resources\ImageCollectionResource;
use App\Http\Resources\ImageResource;
use App\Models\image;

class ImageController extends Controller
{
    public function index()
    {
        return ImageCollectionResource::collection(image::paginate(request()->query('count', 10)));
    }

    public function show(Image $image)
    {
        return new ImageResource($image);
    }

    public function store(StoreImageRequest $request)
    {
        $parts = explode('/', $request->url);
        $dir = implode('/', array_splice($parts, 0, count($parts) - 1));
        if (!file_exists(storage_path('app/public/' . $dir))) {
            mkdir(storage_path('app/public/' . $dir), 0775, true);
        }
        file_put_contents(storage_path('app/public/') . $request->url, $request->image->get());
        image::create($request->validated());
        return response()->json(['message' => 'پیوست با موفقیت ایجاد شد'], 201);
    }
}
