<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public $timestamps = false;

    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = Str::slug($slug);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
