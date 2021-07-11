<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\ImageCollectionResource;
use App\Http\Resources\ImageResource;
use App\Models\Article;
use App\Models\Banner;
use App\Models\image;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $query = image::latest();
        if (request()->filled('group')) {
            $query->whereGroup(request()->group);
        }
        return ImageCollectionResource::collection($query->paginate(request()->query('count', 10)));
    }

    public function show(Image $image)
    {
        return new ImageResource($image);
    }

    public function store(StoreImageRequest $request)
    {
//        $parts = explode('/', $request->url);
//        $dir = implode('/', array_splice($parts, 0, count($parts) - 1));
//        if (!file_exists(storage_path('app/public/' . $dir))) {
//            mkdir(storage_path('app/public/' . $dir), 0775, true);
//        }
//        if (\Image::make($request->image)->filesize() > 2 * 1024) {
//            \Image::make($request->image)->resize(\Image::make($request->image)->width() * 0.5, \Image::make($request->image)->height() * 0.5)->save(storage_path('app/public/') . $request->url);
//        } else {
//            file_put_contents(storage_path('app/public/') . $request->url, $request->image->get());
//        }
        $url = $request->image->store('images');
        image::create(array_merge($request->validated(), ['url' => $url]));
        return response()->json(['message' => 'پیوست با موفقیت ایجاد شد'], 201);
    }

    public function update(UpdateImageRequest $request, image $image)
    {
        if ($request->hasFile('image')) {
//            if (!$image->is_server_serve) {
//                abort(403);
//            }
//            Storage::delete($image->url);
//            $parts = explode('/', $request->url);
//            $dir = implode('/', array_splice($parts, 0, count($parts) - 1));
//            if (!file_exists(storage_path('app/public/' . $dir))) {
//                mkdir(storage_path('app/public/' . $dir), 0775, true);
//            }
//            if (\Image::make($request->image)->filesize() > 2 * 1024) {
//                \Image::make($request->image)->resize(\Image::make($request->image)->width() * 0.5, \Image::make($request->image)->height() * 0.5)->save(storage_path('app/public/') . $request->url);
//            } else {
//                file_put_contents(storage_path('app/public/') . $request->url, $request->image->get());
//            }
            Storage::delete($image->url);
            $url = $request->image->store('images');
            $image->update(array_merge($request->validated(), ['url' => $url]));
        } else {
            $image->update($request->validated());
        }
        return response()->json(['message' => 'پیوست با موفقیت به روزرسانی شد']);
    }

    public function destroy(Image $image)
    {
        if (
            Banner::where('image_id', $image->id)->exists() ||
            ProductCategory::where('image_id', $image->id)->exists() ||
            Product::where('image_id', $image->id)->exists() ||
            Article::where('image_id', $image->id)->exists()
        ) {
            return response()->json(['message' => 'پیوست در حال استفاده است'], 400);
        }
        Storage::delete($image->url);
        $image->delete();
        return response()->json(['message' => 'پیوست با موفقیت حذف شد']);
    }
}
