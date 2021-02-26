<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Page extends Model
{
    use Sortable;

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
