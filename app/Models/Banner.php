<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * used in form requests
     * @var array
     */
    const VALIDATION_NAMES = [
        'location' => 'موقعیت',
        'link' => 'لینک',
    ];

    public $timestamps = false;

    protected $guarded = [];

    public function getLocationAttribute($location)
    {
        return array_search($location, self::LOCATIONS);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
