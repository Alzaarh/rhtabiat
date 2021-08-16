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
 * App\Models\ReturnRequest
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property int $order_id
 * @property string|null $email
 * @property string $reason
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereReason($value)
 */
	class ReturnRequest extends \Eloquent {}
}

