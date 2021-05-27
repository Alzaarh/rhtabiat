<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Resources\ImageCollectionResource;
use App\Models\image;

class ImageController extends Controller
{
    public function index()
    {
        return ImageCollectionResource::collection(image::paginate(request()->query('count', 10)));
    }
    public function store(StoreImageRequest $request)
    {
        $path = config('app.fs_path');
        $parts = explode('/', $request->url);
        $dir = implode('/', array_splice($parts, 0, count($parts) - 1));
        if (!file_exists($path . $dir)) {
            mkdir($path . $dir, 0775, true);
        }
        file_put_contents($path . $request->url, $request->image->get());
        image::create($request->validated());
        return response()->json(['message' => 'پیوست با موفقیت ایجاد شد'], 201);
    }
}
