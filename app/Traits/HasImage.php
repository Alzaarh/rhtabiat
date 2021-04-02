<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    public function getImageAttribute($image)
    {
        return config('app.domain') . ':' . config('app.port') . '/storage/' . $image;
    }

    /**
     * Store the image on disk.
     *
     * @param UploadedFile $image
     * @return string
     */
    public function storeImage(UploadedFile $image): string
    {
        return $image->store('images');
    }

    /**
     * Delete model's image.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    static function deleteImage(\Illuminate\Database\Eloquent\Model $model): void
    {
        Storage::delete($model->getRawOriginal('image'));
    }
}
