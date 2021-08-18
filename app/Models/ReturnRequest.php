<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
