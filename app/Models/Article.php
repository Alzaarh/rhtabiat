<?php

namespace App\Models;

use App\Traits\HasPersianDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Scopes\LatestScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;

class Article extends Model
{
    use HasFactory;

    /*
    #--------------------------------------------------------------------------
    # Properties
    #--------------------------------------------------------------------------
    */

    protected $fillable = [
        'title',
        'slug',
        'short_desc',
        'body',
        'meta',
        'image_id',
        'is_verified',
        'is_waiting',
        'article_category_id',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_waiting' => 'boolean',
    ];

    /*
    #--------------------------------------------------------------------------
    # Events, Scopes, ...
    #--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);
        static::addGlobalScope('available', function (Builder $query) {
            $query->whereIsVerified(true)
                ->whereIsWaiting(false);
        });
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function related()
    {
        $articles = self::where('blog_category_id', $this->blog_category_id)
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->take(4)
            ->get();
        if ($articles->count() < 4) {
            self::whereNotIn('id', $articles->pluck('id'))
                ->inRandomOrder()
                ->take(4 - $articles->count())
                ->get()
                ->each(function ($item, $key) use ($articles) {
                    $articles->push($item);
                });
        }
        return $articles;
    }

    public static function getLatest($count)
    {
        return self::latest()->take($count)->get();
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    /*
    #--------------------------------------------------------------------------
    # Accessors and Mutators
    #--------------------------------------------------------------------------
    */

    public function getCreatedAtAttribute(string $createdAt): string
    {
        return Jalalian::fromCarbon(Carbon::make($createdAt))->ago();
    }
}
