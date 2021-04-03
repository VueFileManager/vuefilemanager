<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereName(string $string)
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'value', 'name'
    ];

    public $timestamps = false;

    protected $primaryKey = 'name';

    protected $keyType = 'string';
}
