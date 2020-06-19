<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserCard
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property int $default
 * @property string $status
 * @property string $card_id
 * @property string $brand
 * @property string $last4
 * @property string $exp_month
 * @property string $exp_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereExpMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereExpYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserCard whereUserId($value)
 * @mixin \Eloquent
 */
class UserCard extends Model
{
    protected $guarded = ['id'];

    public function user() {

        return $this->hasOne(User::class);
    }
}
