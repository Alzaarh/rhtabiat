<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HasImage
{
    /**
     * Prepend correct url to image
     *
     * @param string $image
     * @return string
     */
    public function getImageAttribute(string $image): string
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
}
