<?php

namespace App\Http\Tools;

use App;
use App\Share;
use App\FileManagerFile;
use App\FileManagerFolder;
use App\Http\Requests\FileFunctions\RenameItemRequest;
use App\User;
use ByteUnits\Metric;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;


class Demo
{
    /**
     * Create new directory
     *
     * @param $request
     * @return array
     * @throws \Exception
     */
    public static function create_folder($request)
    {
        // Get variables
        $user_scope = $request->user() ? $request->user()->token()->scopes[0] : 'editor';
        $name = $request->has('name') ? $request->input('name') : 'New Folder';

        return [
            'user_id'    => 1,
            'id'         => random_int(1000, 9999),
            'parent_id'  => random_int(1000, 9999),
            'name'       => $name,
            'type'       => 'folder',
            'unique_id'  => random_int(1000, 9999),
            'user_scope' => $user_scope,
            'items'      => '0',
            'updated_at' => Carbon::now()->format('j M Y \a\t H:i'),
            'created_at' => Carbon::now()->format('j M Y \a\t H:i'),
        ];
    }

    /**
     * Rename item name
     *
     * @param RenameItemRequest $request
     * @param $unique_id
     * @return mixed
     */
    public static function rename_item($request, $unique_id)
    {
        // Get item
        if ($request->type === 'folder') {

            $item = FileManagerFolder::where('unique_id', $unique_id)
                ->where('user_id', 1)
                ->first();

        } else {

            $item = FileManagerFile::where('unique_id', $unique_id)
                ->where('user_id', 1)
                ->first();
        }

        if ($item) {
            $item->name = $request->name;

            return $item;

        } else {

            return [
                'unique_id' => $request->unique_id,
                'name'      => $request->name,
                'type'      => $request->type,
            ];
        }
    }

    /**
     * Upload file
     *
     * @param $request
     * @return array
     * @throws \Exception
     */
    public static function upload($request)
    {
        // Get user data
        $user_scope = $request->user() ? $request->user()->token()->scopes[0] : 'editor';

        // File
        $file = $request->file('file');
        $filename = Str::random() . '-' . str_replace(' ', '', $file->getClientOriginalName());
        $thumbnail = null;
        $filesize = $file->getSize();
        $filetype = get_file_type($file);

        return [
            'id'         => random_int(1000, 9999),
            'unique_id'  => random_int(1000, 9999),
            'folder_id'  => $request->parent_id,
            'thumbnail'  => 'data:' . $request->file('file')->getMimeType() . ';base64, ' . base64_encode(file_get_contents($request->file('file'))),
            'name'       => $file->getClientOriginalName(),
            'basename'   => $filename,
            'mimetype'   => $file->getClientOriginalExtension(),
            'filesize'   => Metric::bytes($filesize)->format(),
            'type'       => $filetype,
            'file_url'   => 'https://vuefilemanager.hi5ve.digital/assets/vue-file-manager-preview.jpg',
            'user_scope' => $user_scope,
            'created_at' => Carbon::now()->format('j M Y \a\t H:i'),
            'updated_at' => Carbon::now()->format('j M Y \a\t H:i'),
        ];
    }

    /**
     * Return 204 status
     *
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public static function response_204() {

        return response('Done!', 204);
    }

    /**
     * Return 204 status
     *
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public static function favourites($user) {

        return $user->favourites->makeHidden(['pivot']);
    }
}