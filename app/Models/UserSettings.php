<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserSettings
 *
 * @property int $id
 * @property int $user_id
 * @property int $storage_capacity
 * @property string|null $billing_name
 * @property string|null $billing_address
 * @property string|null $billing_state
 * @property string|null $billing_city
 * @property string|null $billing_postal_code
 * @property string|null $billing_country
 * @property string|null $billing_phone_number
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereBillingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereBillingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereBillingName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereBillingPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereBillingPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereBillingState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereStorageCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSettings whereUserId($value)
 * @mixin \Eloquent
 */
class UserSettings extends Model
{
    public $timestamps = false;

    protected $guarded = ['id', 'storage_capacity'];
}
