<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guarded = [];

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

    public function updateSelf($data)
    {
        $this->detail()->exists() ?
        $this->updateExistingDetail($data) : $this->createNewDetail($data);
    }

    private function updateExistingDetail($data)
    {
        DB::transaction(function () use ($data) {
            $this->phone = $data['phone'];
            $this->save();
            $data['password'] = $data['new_password'] ?? $data['password'];
            $this->detail->update($data);
        });
    }

    private function createNewDetail($data)
    {
        DB::transaction(function () use ($data) {
            $this->phone = $data['phone'];
            $this->save();
            $this->detail()->save(new UserDetail($data));
        });
    }

    public function canStorePassword()
    {
        return (empty($this->detail) || empty($this->detail->password));
    }

    public function canUpdatePassword($oldPassword)
    {
        return
            $this->canStorePassword() ||
            !Hash::check($oldPassword, $this->detail->password)
                ? false
                : true;
    }
}
