<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class image extends Model
{
    use HasFactory;

    protected $fillable = [
        'alt',
        'title',
        'short_desc',
        'desc',
        'url',
    ];

    public function getCreatedAtFaAttribute()
    {
        return Jalalian::fromCarbon($this->created_at)->format('%B %d, %Y');
    }

    public function getUpdatedAtFaAttribute()
    {
        return Jalalian::fromCarbon($this->updated_at)->format('%B %d, %Y');
    }
}
