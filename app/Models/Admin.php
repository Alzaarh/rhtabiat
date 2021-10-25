<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $hidden = ['password'];

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    public const ROLES = [
        'admin' => 1,
        'accountant' => 2,
        'writer' => 3,
        'discount_generator' => 4,
    ];

    public const ROLES_FA = [
        'مدیر اصلی' => 1,
        'حسابدار' => 2,
        'نویسنده' => 3,
        'مسئول کدتخفیف' => 4,
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the admin's role in string.
     *
     * @return string
     */
    public function getRoleNameAttribute(): string
    {
        return array_search($this->role, self::ROLES);
    }

    /**
     * Hash the admins's password.
     *
     * @param  string  $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function scopeHasRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /*
    #--------------------------------------------------------------------------
    # Relationships
    #--------------------------------------------------------------------------
    */

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLES['admin'];
    }
}
