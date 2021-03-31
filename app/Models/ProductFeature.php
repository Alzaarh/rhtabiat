<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'container',
        'value',
        'price',
    ];

    public $timestamps = false;

    public function scopeZink($query)
    {
        return $query->where('container', 'zink');
    }

    public function scopePlastic($query)
    {
        return $query->where('container', 'plastic');
    }

    public function scopeLightest($query)
    {
        return $query->orderBy('weight', 'asc');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }
}
