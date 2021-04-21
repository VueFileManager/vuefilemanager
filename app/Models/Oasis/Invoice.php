<?php

namespace App\Models\Oasis;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $casts = [
        'items'  => 'array',
        'user'   => 'array',
        'client' => 'array',
    ];

    public $guarded = ['id'];

    public $incrementing = false;

    protected $keyType = 'string';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->id = Str::uuid();

            $invoice->delivery_at = $invoice->created_at;
            $invoice->due_at = Carbon::parse($invoice->created_at)->addWeeks(2);

            $invoice->total_discount = invoice_total_discount($invoice);
            $invoice->total_net = invoice_total_net($invoice);
            $invoice->total_tax = invoice_total_tax($invoice);
        });
    }
}
