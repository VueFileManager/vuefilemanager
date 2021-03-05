<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
    ];

    public $timestamps = false;

    protected $primaryKey = 'name';

    protected $keyType = 'string';
}
