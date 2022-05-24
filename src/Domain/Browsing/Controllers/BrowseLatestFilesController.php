<?php
namespace Domain\Browsing\Controllers;

use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrowseLatestFilesController
{
    public function __invoke(Request $request): array
    {
        $user = User::with([
            'latestUploads' => fn ($query) => $query->sortable(['created_at' => 'desc']),
        ])
            ->where('id', Auth::id())
            ->first();

        list($data, $paginate, $links) = groupPaginate($request, null, $user->latestUploads);

        return [
            'data'  => $data,
            'links' => $links,
            'meta'  => [
                'paginate' => $paginate,
                'root'     => null,
            ],
        ];
    }
}
