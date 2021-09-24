<?php
namespace Support\Demo\Actions;

use Illuminate\Http\Request;
use Domain\Folders\Models\Folder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class FakeCreateFolderAction
{
    /**
     * Create new fake folder
     */
    public function __invoke(
        Request $request
    ): Collection | Model {
        return Folder::factory()
            ->make([
                'name'       => $request->input('name'),
                'author'     => $request->user() ? 'user' : 'visitor',
                'color'      => $request->input('icon.color') ?? null,
                'emoji'      => $request->input('icon.emoji') ?? null,
                'updated_at' => now()->format(__t('time')),
                'created_at' => now()->format(__t('time')),
            ]);
    }
}
