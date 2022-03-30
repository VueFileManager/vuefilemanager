<?php
namespace Domain\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string version
 */
class AppUpdate extends Model
{
    public $guarded = [
        'id',
    ];
}
