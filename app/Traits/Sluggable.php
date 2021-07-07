<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = Str::slug($slug);
    }
}
