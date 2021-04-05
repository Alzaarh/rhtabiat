<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Imageable
{
    public function getImageAttribute($imagePath)
    {
        return empty($imagePath)
            ? null
            : Str::of(config('app.domain'))
                ->append(':')
                ->append(config('app.port'))
                ->append('/storage/')
                ->append($imagePath);
    }

    public function handleImageUpload(UploadedFile $image = null) : void
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
