<?php
namespace Domain\Browsing\Controllers;

use App\Users\Models\User;
use Domain\Files\Resources\FilesCollection;
use Illuminate\Support\Facades\Auth;

class BrowseLatestFilesController
{
    public function __invoke(): array
    {
        $user = User::with([
            'latestUploads' => fn ($query) => $query->sortable(['created_at' => 'desc']),
        ])
            ->where('id', Auth::id())
            ->first();

        return [
            'files'   => new FilesCollection($user->latestUploads),
            'root'    => null,
        ];
    }
}
