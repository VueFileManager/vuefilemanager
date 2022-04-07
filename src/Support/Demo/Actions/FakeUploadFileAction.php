<?php
namespace Support\Demo\Actions;

use ByteUnits\Metric;
use Domain\Sharing\Resources\ShareResource;
use Illuminate\Support\Str;
use Domain\Files\Requests\UploadRequest;

class FakeUploadFileAction
{
    /**
     * Upload file
     */
    public function __invoke(
        UploadRequest $request
    ): array {
        $file = $request->file('file');
        $thumbnail = 'data:' . $request->file('file')->getMimeType() . ';base64, ' . base64_encode(file_get_contents($request->file('file')));

        return [
            'data' => [
                'id'            => Str::uuid(),
                'type'          => get_file_type($file->getMimeType()),
                'attributes'    => [
                    'filesize'      => Metric::bytes($file->getSize())->format(),
                    'name'          => $request->input('filename'),
                    'basename'      => $request->input('filename'),
                    'mimetype'      => $file->getClientOriginalExtension(),
                    'file_url'      => 'https://vuefilemanager.hi5ve.digital/assets/vue-file-manager-preview.jpg',
                    'thumbnail'     => [
                        'xs' => $thumbnail,
                        'sm' => $thumbnail,
                        'lg' => $thumbnail,
                        'xl' => $thumbnail,
                    ],
                    'parent_id'     => $request->input('parent_id'),
                    'created_at'    => format_date(now()),
                    'updated_at'    => format_date(now()),
                    'deleted_at'    => null,
                ],
                'relationships' => [
                    'creator' => null,
                ],
            ],
        ];
    }
}
