<?php

namespace App\Models;

use App\Traits\HasPersianDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;
    use HasPersianDate;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'meta',
        'image',
        'article_category_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(
            'latest',
            fn (Builder $builder) =>
            $builder->latest('updated_at')
        );

        static::deleting(
            fn ($article) =>
            Storage::delete($article->getRawOriginal('image'))
        );

        static::saving(function ($article) {
            $article->slug = makeSlug($article->title);
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
}
