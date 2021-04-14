<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    use HasImage;

    public $timestamps = false;

    protected $guarded = [];

    const LOCATIONS = [
        'hero' => 1,
        'home_top_big' => 2,
        'home_top_small' => 3,
        'home_below' => 4,
        'home_mob_slider' => 5,
        'home_mob_small' => 6,
    ];

    public function getLocationAttribute($location)
    {
        return array_search($location, self::LOCATIONS);
    }
}
