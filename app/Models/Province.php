<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Province
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereName($value)
 * @mixin \Eloquent
 */
class Province extends Model
{
    use HasFactory;
}
