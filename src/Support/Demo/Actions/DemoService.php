<?php
namespace App\Services;

use App\Models\File;
use ByteUnits\Metric;
use App\Models\Folder;
use Illuminate\Support\Str;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests\FileFunctions\RenameItemRequest;

class DemoService
{
    /**
     * Create new directory
     *
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function create_folder($request)
    {
        return [
            'user_id'    => 1,
            'id'         => Str::uuid(),
            'parent_id'  => random_int(1000, 9999),
            'name'       => $request->name,
            'type'       => 'folder',
            'author'     => $request->user() ? 'user' : 'visitor',
            'items'      => '0',
            'color'      => isset($request->icon['color']) ? $request->icon['color'] : null,
            'emoji'      => isset($request->icon['emoji']) ? $request->icon['emoji'] : null,
            'updated_at' => now()->format('j M Y \a\t H:i'),
            'created_at' => now()->format('j M Y \a\t H:i'),
        ];
    }

    /**
     * Rename item name
     *
     * @param RenameItemRequest $request
     * @param $id
     * @return mixed
     */
    public function rename_item($request, $id)
    {
        // Get item
        if ($request->type === 'folder') {
            $item = Folder::where('id', $id)
                ->where('user_id', 1)
                ->first();
        } else {
            $item = File::where('id', $id)
                ->where('user_id', 1)
                ->first();
        }

        if ($item) {
            $item->name = $request->name;
            $item->emoji = $request->icon['emoji'] ?? null;
            $item->color = $request->icon['color'] ?? null;

            return $item;
        }

        return [
            'id'   => $request->id,
            'name' => $request->name,
            'type' => $request->type,
        ];
    }

    /**
     * Upload file
     *
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function upload($request)
    {
        // File
        $file = $request->file('file');
        $filename = Str::random() . '-' . str_replace(' ', '', $file->getClientOriginalName());
        $thumbnail = null;
        $filesize = $file->getSize();
        $filetype = get_file_type($file->getMimeType());

        return [
            'id'         => Str::uuid(),
            'folder_id'  => $request->parent_id,
            'thumbnail'  => 'data:' . $request->file('file')->getMimeType() . ';base64, ' . base64_encode(file_get_contents($request->file('file'))),
            'name'       => $file->getClientOriginalName(),
            'basename'   => $filename,
            'mimetype'   => $file->getClientOriginalExtension(),
            'filesize'   => Metric::bytes($filesize)->format(),
            'type'       => $filetype,
            'file_url'   => 'https://vuefilemanager.hi5ve.digital/assets/vue-file-manager-preview.jpg',
            'author'     => $request->user() ? 'user' : 'visitor',
            'created_at' => now()->format('j M Y \a\t H:i'),
            'updated_at' => now()->format('j M Y \a\t H:i'),
        ];
    }

    /**
     * Return 204 status
     *
     * @param $user
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function favourites($user)
    {
        return $user->favouriteFolders->makeHidden(['pivot']);
    }
}
