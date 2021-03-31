<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    private function createDetail($data)
    {
        $this->detail()->save(new UserDetail($data));
    }

    private function updateDetail($data)
    {
        if (isset($data['newPassword'])) {
            if (!$this->detail->passwordsMatch($data['oldPassword'])) {
                throw ValidationException::withMessages([]);
            }

            $data['password'] = $data['newPassword'];
        }
        $this->detail->update($data);
    }

    private function updatePhone($phone)
    {
        $this->phone = $phone;
        $this->save();
    }

    public function scopewherePhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    public function hasDetail()
    {
        return (bool) $this->detail;
    }

    public function newDetail($data)
    {
        $this->when(!$this->hasDetail(), function () use ($data) {
            DB::transaction(function () use ($data) {
                $this->createDetail($data);
                $this->updatePhone($data['phone']);
            });
        }, function () use ($data) {
            DB::transaction(function () use ($data) {
                $this->updateDetail($data);
                $this->updatePhone($data['phone']);
            });
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::create($value)->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::create($value)->toDateTimeString();
    }

    public function newUser($phone)
    {
        return auth()->login(self::create(['phone' => $phone]));
    }

    public function newAddress($data)
    {
        return $this->addresses()->save(new Address($data));
    }

    public function findWithPhone($phone)
    {
        return $this->wherePhone($phone)->first();
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Address::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
