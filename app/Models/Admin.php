<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $hidden = ['password'];

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    const ROLES = [
        'admin' => 1,
        'accountant' => 2,
        'discountGenerator' => 3,
        'writer' => 4,
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getRoleNameAttribute()
    {
        return array_search($this->attributes['role'], self::ROLES);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function scopeHasRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function isAdmin()
    {
        return $this->role === self::ROLES['admin'];
    }

    public static function auth(array $credentials): string
    {
        $token = auth('admin')->attempt($credentials);
        if (!$token) {
            throw ValidationException::withMessages([
                'username' => 'Username or password is invalid',
            ]);
        }
        return $token;
    }
}
