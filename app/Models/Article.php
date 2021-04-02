<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'title',
        'body',
        'meta',
        'thumbnail',
        'blog_category_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest('updated_at');
        });
        static::deleted(function ($article) {
            Storage::delete($article->getAttributes()['thumbnail']);
        });
    }

    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }

    public function getThumbnailAttribute($value)
    {
        return filled($value) ? storage() . $value : null;
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
