<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\PromoCode
 *
 * @property int $id
 * @property string $code
 * @property bool $user_only
 * @property bool $one_per_user
 * @property int|null $off_percent
 * @property int|null $off_value
 * @property int|null $max
 * @property int|null $min
 * @property bool $infinite
 * @property int|null $count
 * @property string $valid_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $valid_date_fa
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode newQuery()
 * @method static \Illuminate\Database\Query\Builder|PromoCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereInfinite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereOffPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereOffValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereOnePerUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereUserOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereValidDate($value)
 * @method static \Illuminate\Database\Query\Builder|PromoCode withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PromoCode withoutTrashed()
 */
	class PromoCode extends \Eloquent {}
}

