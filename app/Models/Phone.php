<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Phone
 *
 * @property int $id
 * @property string $phone
 * @property string $code
 * @property string $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Phone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Phone extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'num',
        'code'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
    ];
}
