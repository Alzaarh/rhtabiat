<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Poster extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    const LOCATIONS = [
        'dashboard' => 1,
    ];

    protected static function booted()
    {
        static::deleting(function ($poster) {
            Storage::delete($poster->getRawOriginal('image'));
        });
    }

    public function getImageAttribute($value)
    {
        return filled($value) ? storage() . $value : null;
    }
}
