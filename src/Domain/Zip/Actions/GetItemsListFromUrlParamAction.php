<?php
namespace Domain\Zip\Actions;

use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;

class GetItemsListFromUrlParamAction
{
    public function __invoke(): array
    {
        $list = explode(',', request()->get('items'));

        $itemList = collect($list)
            ->map(function ($chunk) {
                $items = explode('|', $chunk);

                return [
                    'id'   => $items[0],
                    'type' => $items[1],
                ];
            });

        $folderIds = $itemList
            ->where('type', 'folder')
            ->pluck('id');

        $fileIds = $itemList
            ->where('type', 'file')
            ->pluck('id');

        $folders = Folder::whereIn('id', $folderIds)
            ->get();

        $files = File::whereIn('id', $fileIds)
            ->get();

        return [$folders, $files];
    }
}
