<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Invoice
 *
 * @property int $id
 * @property string $token
 * @property string $order
 * @property string|null $provider
 * @property string $user_id
 * @property string $plan_id
 * @property array $seller
 * @property array $client
 * @property array $bag
 * @property string|null $notes
 * @property string $total
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereBag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereSeller($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereUserId($value)
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'seller' => 'array',
        'client' => 'array',
        'bag'    => 'array',
    ];

    /**
     * Get user instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
