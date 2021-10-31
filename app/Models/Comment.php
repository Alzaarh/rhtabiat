<?php

namespace App\Models;

use App\Traits\HasPersianDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\LatestScope;

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
 * @property int $is_testimonial
 * @property-read string $created_at_fa
 * @property-read string $status_fa
 * @method static Builder|Comment testimonials()
 * @method static Builder|Comment whereIsTestimonial($value)
 */
class Comment extends Model
{
    use HasFactory;
    use HasPersianDate;

    public const NOT_VERIFIED = 1;
    public const VERIFIED = 2;
    public const REJECTED = 3;
    protected const STATUS_FA = [
        1 => 'در انتظار تایید',
        2 => 'تایید شده',
        3 => 'رد شده',
    ];
    public $timestamps = false;
    protected $fillable = [
        'author_name',
        'author_email',
        'body',
        'score',
        'status',
    ];

    public static function getLatest($count)
    {
        return self::latest()->take($count)->get();
    }

    protected static function booted()
    {
        static::addGlobalScope('verified', function (Builder $builder) {
            $builder->where('status', self::VERIFIED);
        });

        static::creating(function ($comment) {
            $comment->status ??= self::NOT_VERIFIED;
        });

        static::addGlobalScope(new LatestScope);
    }

    public function getResourceTypeAttribute(): string
    {
        return explode('\\', $this->commentable_type)[2];
    }

    public function getStatusFaAttribute(): string
    {
        return self::STATUS_FA[$this->status];
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function scopeTestimonials($query)
    {
        return $query->whereIsTestimonial(true);
    }

    public function verify(): void
    {
        $this->status = self::VERIFIED;
        $this->save();
    }

    public function reject(): void
    {
        $this->status = self::REJECTED;
        $this->save();
    }
}
