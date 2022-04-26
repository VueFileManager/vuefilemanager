<?php
namespace App\Users\Models;

use DB;
use ByteUnits\Metric;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserLimitationFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int max_storage_amount
 * @property int max_team_members
 * @property string user_id
 */
class UserLimitation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'user_id', 'user',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): UserLimitationFactory
    {
        return UserLimitationFactory::new();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get summary of user limitations and their usage
     */
    public function summary(): array
    {
        return [
            'max_storage_amount' => $this->getMaxStorageAmount(),
        ];
    }

    /**
     * Get usage data of user storage
     */
    private function getMaxStorageAmount(): array
    {
        $userCapacity = $this->user->usedCapacity;

        return [
            'use'        => Metric::bytes($userCapacity)->format(),
            'total'      => format_gigabytes($this->max_storage_amount),
            'percentage' => get_storage_percentage($userCapacity, $this->max_storage_amount),
        ];
    }
}
