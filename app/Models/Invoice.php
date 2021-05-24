<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'seller' => 'array',
        'client' => 'array',
        'bag'    => 'array',
    ];

    public function getMimetype()
    {
        return 'pdf';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
