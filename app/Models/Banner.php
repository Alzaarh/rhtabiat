<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Banner
 *
 * @property int $id
 * @property string $image
 * @property int $location
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereLocation($value)
 * @mixin \Eloquent
 */
class Banner extends Model
{
    use HasFactory;

    const LOCATIONS = [
        'hero' => 1,
        'home_top_big' => 2,
        'home_top_small' => 3,
        'home_below' => 4,
        'home_mob_slider' => 5,
        'home_mob_small' => 6,
    ];

    public $timestamps = false;

    protected $guarded = [];

    public function getLocationAttribute($location)
    {
        return array_search($location, self::LOCATIONS);
    }
}
