<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['link'];

    /**
     * Generate share link
     *
     * @return string
     */
    public function getLinkAttribute() {

        return url('/shared', ['token' => $this->attributes['token']]);
    }
}
