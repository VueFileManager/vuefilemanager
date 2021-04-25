<?php
namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use Sortable, HasFactory;

    /**
     * Sortable columns
     *
     * @var string[]
     */
    public $sortable = [
        'title',
        'slug',
        'visibility',
    ];

    public $fillable = [
        'slug',
        'title',
        'visibility',
        'content',
    ];

    protected $primaryKey = 'slug';

    protected $keyType = 'string';

    public $timestamps = false;
}
