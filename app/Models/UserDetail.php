<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['user_id'];

    protected $touches = ['user'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function passwordsMatch($old)
    {
        if (!Hash::check($old, $this->password))
            return false;
        return true;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
