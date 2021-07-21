<?php
namespace Support\Demo\Actions;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FakeCreateFolderAction
{
    /**
     * Create new directory
     */
    public function __invoke(
        Request $request
    ): array {
        return [
            'user_id'    => 1,
            'id'         => Str::uuid(),
            'parent_id'  => random_int(1000, 9999),
            'name'       => $request->input('name'),
            'type'       => 'folder',
            'author'     => $request->user() ? 'user' : 'visitor',
            'items'      => '0',
            'color'      => $request->input('icon.color') ?? null,
            'emoji'      => $request->input('icon.emoji') ?? null,
            'updated_at' => now()->format(__t('time')),
            'created_at' => now()->format(__t('time')),
        ];
    }
}
