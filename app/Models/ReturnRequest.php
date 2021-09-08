<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class ReturnRequest extends Model
{
    use HasFactory;

    /*

    #--------------------------------------------------------------------------
    # Properties
    #--------------------------------------------------------------------------

    */

    public $timestamps = false;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'reason',
    ];

    /*
    #--------------------------------------------------------------------------
    # Accessors and Mutators
    #--------------------------------------------------------------------------
    */

    public function getCreatedAtAttribute(string $createdAt): string
    {
        return Jalalian::fromCarbon(Carbon::make($createdAt))->format('Y/m/d');
    }

    /*

    #--------------------------------------------------------------------------
    # Other Stuff
    #--------------------------------------------------------------------------

    */

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
