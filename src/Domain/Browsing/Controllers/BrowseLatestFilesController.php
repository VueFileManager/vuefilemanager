<?php
namespace Domain\Browsing\Controllers;

use App\Users\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BrowseLatestFilesController
{
    public function __invoke(): Collection
    {
        $user = User::with([
            'latestUploads' => fn ($query) => $query->sortable(['created_at' => 'desc']),
        ])
            ->where('id', Auth::id())
            ->first();

        return $user->latestUploads;
    }
}
