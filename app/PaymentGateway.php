<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PaymentGateway
 *
 * @property int $id
 * @property int $status
 * @property int $sandbox
 * @property string $name
 * @property string $slug
 * @property string $logo
 * @property string|null $client_id
 * @property string|null $secret
 * @property string|null $webhook
 * @property string|null $optional
 * @property int|null $payment_processed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereOptional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway wherePaymentProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereSandbox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentGateway whereWebhook($value)
 * @mixin \Eloquent
 */
class PaymentGateway extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;
}
