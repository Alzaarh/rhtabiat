<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property string $created_at
 * @method static \Database\Factories\MessageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message wherePhone($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function getCreatedAtAttribute(string $createdAt): string
    {
        return Jalalian::fromCarbon(Carbon::make($createdAt))->ago();
    }

    /*
    #--------------------------------------------------------------------------
    # Events, Scopes, ...
    #--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope(new LatestScope);
    }
}
