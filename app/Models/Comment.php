<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'author_email',
        'body',
        'score',
        'status',
    ];

    const NOT_VERIFIED = 1;
    const VERIFIED = 2;
    const REJECTED = 3;

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('verified', function (Builder $builder) {
            $builder->where('status', self::VERIFIED);
        });
    }

    public function setStatusAttribute($status)
    {
        $this->attributes['status'] = $status ?? self::NOT_VERIFIED;
    }

    public function getResourceTypeAttribute()
    {
        return Str::of($this->commentable_type)->explode('\\')->last();
    }

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
