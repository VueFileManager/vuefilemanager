<?php
namespace Domain\Files\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exif extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'longitude' => 'array',
        'latitude'  => 'array',
    ];

    /**
     * Get parent
     */
    public function file(): HasOne
    {
        return $this->HasOne(File::class, 'id', 'file_id');
    }

    public static function boot()
    {
        parent::boot();
 
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
