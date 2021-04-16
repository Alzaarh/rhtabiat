<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\UserDetail|null $detail
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function orders()
    {
        return $this->hasMany(Order::class);
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

    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    private function updateExistingDetail($data)
    {
        DB::transaction(
            function () use ($data) {
                $this->phone = $data['phone'];
                $this->save();
                $data['password'] = $data['new_password'] ?? $data['password'];
                $this->detail->update($data);
            }
        );
    }

    private function createNewDetail($data)
    {
        DB::transaction(
            function () use ($data) {
                $this->phone = $data['phone'];
                $this->save();
                $this->detail()->save(new UserDetail($data));
            }
        );
    }

    public function canUpdatePassword(string $oldPassword): bool
    {
        return !($this->canStorePassword() || !Hash::check($oldPassword, $this->detail->password));
    }

    public function canStorePassword(): bool
    {
        return empty($this->detail) || empty($this->detail->password);
    }

    public function isCartEmpty(): bool
    {
        return $this->cart->products()->count() === 0;
    }

    public function hasAddress($id): bool
    {
        return $this->addresses()->where('id', $id)->exists();
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
