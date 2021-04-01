<?php

namespace App\Traits;

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
}
