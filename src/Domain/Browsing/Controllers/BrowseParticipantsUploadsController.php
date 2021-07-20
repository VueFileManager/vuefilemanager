<?php
namespace Domain\Browsing\Controllers;

use Domain\Files\Models\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BrowseParticipantsUploadsController
{
    public function __invoke(): Collection
    {
        return File::with(['parent'])
            ->where('user_id', Auth::id())
            ->whereAuthor('visitor')
            ->sortable()
            ->get();
    }
}
