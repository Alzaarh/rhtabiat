<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use HasFactory;

    /**
     * Value of the status column, not verified yet comment.
     * 
     * @var int
     */
    const NOT_VERIFIED = 1;

    /**
     * Value of the status column, verified comment.
     * 
     * @var int
     */
    const VERIFIED = 2;

    /**
     * Value of the status column, rejected comment.
     * 
     * @var int
     */
    const REJECTED = 3;

    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    // protected static function booted()
    // {
    //     static::addGlobalScope('verified', function (Builder $builder) {
    //         $builder->where('status', self::STATUSLIST['verified']);
    //     });
    // }

    /**
     * Get the parent commentable model (product or article).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public static function getLatest($count)
    {
        return self::latest()->take($count)->get();
    }
}
