<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserDetail
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserDetailFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereUsername($value)
 * @mixin \Eloquent
 */
class UserDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = ['id', 'password', 'user_id'];

    protected $fillable = ['name', 'username', 'email'];

    protected $touches = ['user'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
