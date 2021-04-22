<?php

namespace App\Models;

use App\Traits\HasPersianDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $author_name
 * @property string $author_email
 * @property string $body
 * @property int $score
 * @property int $status
 * @property string $commentable_type
 * @property int $commentable_id
 * @property string $created_at
 * @property-read Model|\Eloquent $commentable
 * @property-read mixed $resource_type
 * @method static \Database\Factories\CommentFactory factory(...$parameters)
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereAuthorEmail($value)
 * @method static Builder|Comment whereAuthorName($value)
 * @method static Builder|Comment whereBody($value)
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereScore($value)
 * @method static Builder|Comment whereStatus($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use HasFactory;
    use HasPersianDate;

    protected $fillable = [
        'author_name',
        'author_email',
        'body',
        'score',
        'status',
    ];

    public const NOT_VERIFIED = 1;

    public const VERIFIED = 2;

    public const REJECTED = 3;

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

    public function commentable()
    {
        return $this->morphTo();
    }

    public static function getLatest($count)
    {
        return self::latest()->take($count)->get();
    }

    public function scopeTestimonials($query)
    {
        return $query->whereIsTestimonial(true);
    }
}
