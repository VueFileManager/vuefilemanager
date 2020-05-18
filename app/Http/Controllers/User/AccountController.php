<?php

namespace App\Http\Controllers\User;

use App\FileManagerFile;
use App\FileManagerFolder;
use App\Http\Resources\StorageDetailResource;
use App\Http\Tools\Demo;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use ByteUnits\Metric;
use App\User;

class AccountController extends Controller
{
    /**
     * Get all user data to frontend
     *
     * @return array
     */
    public function user()
    {
        // Get User
        $user = User::with(['favourites', 'latest_uploads'])
            ->where('id', Auth::id())
            ->first();

        // Get folder tree
        $tree = FileManagerFolder::with(['folders.shared', 'shared:token,id,item_id,permission,protected'])
            ->where('parent_id', 0)
            ->where('user_id', $user->id)
            ->get();

        return [
            'user'       => $user->only(['name', 'email', 'avatar']),
            'favourites' => $user->favourites->makeHidden(['pivot']),
            'tree'       => $tree,
            'storage'    => [
                'used'       => Metric::bytes($user->used_capacity)->format(),
                'capacity'   => format_gigabytes(config('vuefilemanager.user_storage_capacity')),
                'percentage' => get_storage_fill_percentage($user->used_capacity, config('vuefilemanager.user_storage_capacity')),
            ],
        ];
    }

    /**
     * Get storage details
     *
     * @return array
     */
    public function storage()
    {
        $document_mimetypes = [
            'pdf', 'numbers', 'xlsx', 'xls', 'txt', 'md', 'rtf', 'pptx', 'ppt', 'odt', 'ods', 'odp', 'epub', 'docx', 'doc', 'csv', 'pages'
        ];

        $user = Auth::user();
        $storage_capacity = config('vuefilemanager.user_storage_capacity');

        $images = FileManagerFile::where('user_id', $user->id)
            ->where('type', 'image')->get()->map(function ($item) {
                return (int)$item->getOriginal('filesize');
            })->sum();

        $audios = FileManagerFile::where('user_id', $user->id)
            ->where('type', 'audio')->get()->map(function ($item) {
                return (int)$item->getOriginal('filesize');
            })->sum();

        $videos = FileManagerFile::where('user_id', $user->id)
            ->where('type', 'video')->get()->map(function ($item) {
                return (int)$item->getOriginal('filesize');
            })->sum();

        $documents = FileManagerFile::where('user_id', $user->id)
            ->whereIn('mimetype', $document_mimetypes)->get()->map(function ($item) {
                return (int)$item->getOriginal('filesize');
            })->sum();

        $others = FileManagerFile::where('user_id', $user->id)
            ->whereNotIn('mimetype', $document_mimetypes)
            ->whereNotIn('type', ['audio', 'video', 'image'])
            ->get()->map(function ($item) {
                return (int)$item->getOriginal('filesize');
            })->sum();

        $usage = collect([
            'images'    => [
                'used'       => $images,
                'percentage' => get_storage_fill_percentage($images, $storage_capacity),
            ],
            'audios'    => [
                'used'       => $audios,
                'percentage' => get_storage_fill_percentage($audios, $storage_capacity),
            ],
            'videos'    => [
                'used'       => $videos,
                'percentage' => get_storage_fill_percentage($videos, $storage_capacity),
            ],
            'documents' => [
                'used'       => $documents,
                'percentage' => get_storage_fill_percentage($documents, $storage_capacity),
            ],
            'others'    => [
                'used'       => $others,
                'percentage' => get_storage_fill_percentage($others, $storage_capacity),
            ],
        ]);

        return [
            'data' => [
                'id'            => '1',
                'type'          => 'disk',
                'attributes'    => [
                    'used'       => Metric::bytes($user->used_capacity)->format(),
                    'capacity'   => format_gigabytes($storage_capacity),
                    'percentage' => get_storage_fill_percentage($user->used_capacity, $storage_capacity),
                ],
                'relationships' => $usage->map(function ($item) {
                    return [
                        'used'       => Metric::bytes($item['used'])->format(),
                        'percentage' => $item['percentage']
                    ];
                })
            ]
        ];
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'avatar' => 'file',
            'name'   => 'string',
            'value'  => 'string',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user
        $user = Auth::user();

        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        if ($request->hasFile('avatar')) {

            // Update avatar
            $avatar = store_avatar($request->file('avatar'), 'avatars');

            // Update data
            $user->update(['avatar' => $avatar]);

        } else {

            // Update text data
            $user->update(make_single_input($request));
        }

        return response('Saved!', 204);
    }

    /**
     * Change user password
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function change_password(Request $request)
    {
        // Validate request
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Get user
        $user = Auth::user();

        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Change and store new password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response('Changed!', 204);
    }
}
