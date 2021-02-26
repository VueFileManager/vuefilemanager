<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

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

    public $timestamps = false;
}
