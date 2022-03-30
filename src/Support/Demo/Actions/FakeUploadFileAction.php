<?php
namespace Support\Demo\Actions;

use ByteUnits\Metric;
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
        $filename = Str::random() . '-' . str_replace(' ', '', $file->getClientOriginalName());
        $thumbnail = 'data:' . $request->file('file')->getMimeType() . ';base64, ' . base64_encode(file_get_contents($request->file('file')));

        $fileType = get_file_type($file->getMimeType());
        $fileSize = Metric::bytes($file->getSize())->format();

        return [
            'id'         => Str::uuid(),
            'parent_id'  => $request->input('parent_id'),
            'thumbnail'  => $thumbnail,
            'name'       => $file->getClientOriginalName(),
            'basename'   => $filename,
            'mimetype'   => $file->getClientOriginalExtension(),
            'file_url'   => 'https://vuefilemanager.hi5ve.digital/assets/vue-file-manager-preview.jpg',
            'created_at' => now()->format(__t('time')),
            'updated_at' => now()->format(__t('time')),
            'type'       => $fileType,
            'filesize'   => $fileSize,
        ];
    }
}
