<?php

namespace App\Models\Oasis;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;

class Invoice extends Model
{
    use HasFactory, Searchable;

    protected $casts = [
        'items'  => 'array',
        'user'   => 'array',
        'client' => 'array',
    ];

    public $guarded = [
        'id'
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'user_id');
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        $client_name = Str::slug($array['client']['name'], ' ');

        return [
            'id'                  => $this->id,
            'clientName'          => $array['client']['name'],
            'clientNameNgrams'    => utf8_encode((new TNTIndexer)->buildTrigrams(implode(', ', [$client_name]))),
            'invoiceNumber'       => $array['invoice_number'],
            'invoiceNumberNgrams' => utf8_encode((new TNTIndexer)->buildTrigrams(implode(', ', [$array['invoice_number']]))),
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->id = (string)Str::uuid();

            $invoice->delivery_at = $invoice->created_at;
            $invoice->due_at = Carbon::parse($invoice->created_at)->addWeeks(2);

            $invoice->total_discount = invoice_total_discount($invoice);
            $invoice->total_net = invoice_total_net($invoice);
            $invoice->total_tax = invoice_total_tax($invoice);

            $invoice->currency = 'CZK';
        });
    }
}
