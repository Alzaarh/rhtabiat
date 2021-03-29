<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
