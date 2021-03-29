<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use HasFactory;

    const STATUSLIST = [
        'toBeVerified' => 1, 
        'verified' => 2,
        'rejected' => 3,
    ];

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('verified', function (Builder $builder) {
            $builder->where('status', self::STATUSLIST['verified']);
        });
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public static function getLatest($count)
    {
        return self::latest()->take($count)->get();
    }
}
