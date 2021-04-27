<?php

namespace App\Models\Oasis;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvoiceProfile extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public $incrementing = false;

    protected $keyType = 'string';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->id = (string) Str::uuid();
        });
    }
}
