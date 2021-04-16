<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Poster
 *
 * @property int $id
 * @property string $image
 * @property int $location
 * @property int $is_active
 * @method static \Database\Factories\PosterFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster query()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereLocation($value)
 * @mixin \Eloquent
 */
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
