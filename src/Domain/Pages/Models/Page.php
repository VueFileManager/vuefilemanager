<?php
namespace Domain\Pages\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string slug
 * @property string title
 * @property bool visibility
 * @property string content
 */
class Page extends Model
{
    use Sortable, HasFactory;

    public array $sortable = [
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
