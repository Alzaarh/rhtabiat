<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\ImageCollectionResource;
use App\Http\Resources\ImageResource;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $query = Image::latest();
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
        $image = new Image($request->validated());
        $image->resize();
        $image->upload();
        $image->save();

        return response()->json(['message' => 'پیوست با موفقیت ایجاد شد'], 201);
    }

    public function update(UpdateImageRequest $request, Image $image)
    {
        $image->update($request->only([
            'title',
            'alt',
            'short_desc',
            'desc',
            'group',
        ]));

        if ($request->hasFile('image')) {
            $image->image = $request->file('image');
            $image->deleteImage();
            $image->resize();
            $image->url = $request->input('url') ?? $image->url;
            $image->upload();
            $image->save();
        }

        return response()->json(['message' => 'پیوست با موفقیت به روزرسانی شد']);
    }

    public function destroy(Image $image)
    {
        if ($image->isInUse()) {
            return response()->json(['message' => 'پیوست در حال استفاده است'], 400);
        }
        $image->deleteImage();
        $image->delete();

        return response()->json(['message' => 'پیوست با موفقیت حذف شد']);
    }
}
