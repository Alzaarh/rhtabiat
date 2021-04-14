<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    public function getImageAttribute($imagePath): ?string
    {
        return empty($imagePath) ? null : '/' . $imagePath;
    }

    public function handleImageUpload(UploadedFile $image = null): void
    {
        if (filled($image)) {
            if (filled($this->image)) {
                Storage::delete($this->getRawOriginal('image'));
            }
            $this->image = $image->store('images');
        } else {
            $this->image = null;
        }
    }
}
