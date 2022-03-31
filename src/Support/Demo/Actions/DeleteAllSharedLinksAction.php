<?php
namespace Support\Demo\Actions;

use DB;

class DeleteAllSharedLinksAction
{
    public function __invoke()
    {
        DB::table('shares')->delete();
    }
}
