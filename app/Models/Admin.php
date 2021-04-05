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

    /**
     * Value of the role column.
     *
     * @var int
     */
    const ROLES = [
        'admin' => 1,
        'accountant' => 2,
        'writer' => 3,
        'discount_generator' => 4,
    ];
    
    // const ADMIN = 1;
    // const ACCOUNTANT = 2;
    // const WRITER = 3;
    // const DISCOUNT_GENERATOR = 4;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
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

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLES['admin'];
    }
}
