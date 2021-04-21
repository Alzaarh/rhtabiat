<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ArticleCategory
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property-read \Illuminate\Database\Eloquent\Collection|Article[] $articles
 * @property-read int|null $articles_count
 * @method static \Database\Factories\ArticleCategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereSlug($value)
 * @mixin \Eloquent
 */
class ArticleCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::saving(
            fn($article) => $article->slug = makeSlug($article->name)
        );
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
