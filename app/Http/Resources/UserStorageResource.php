<?php

namespace App\Http\Resources;

use App\FileManagerFile;
use ByteUnits\Metric;
use Illuminate\Http\Resources\Json\JsonResource;

class UserStorageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $document_mimetypes = [
            'pdf', 'numbers', 'xlsx', 'xls', 'txt', 'md', 'rtf', 'pptx', 'ppt', 'odt', 'ods', 'odp', 'epub', 'docx', 'doc', 'csv', 'pages'
        ];

        // Get all images
        $images = FileManagerFile::where('user_id', $this->id)
            ->where('type', 'image')->get()->map(function ($item) {
                return (int)$item->getRawOriginal('filesize');
            })->sum();

        // Get all audios
        $audios = FileManagerFile::where('user_id', $this->id)
            ->where('type', 'audio')->get()->map(function ($item) {
                return (int)$item->getRawOriginal('filesize');
            })->sum();

        // Get all videos
        $videos = FileManagerFile::where('user_id', $this->id)
            ->where('type', 'video')->get()->map(function ($item) {
                return (int)$item->getRawOriginal('filesize');
            })->sum();

        // Get all documents
        $documents = FileManagerFile::where('user_id', $this->id)
            ->whereIn('mimetype', $document_mimetypes)->get()->map(function ($item) {
                return (int)$item->getRawOriginal('filesize');
            })->sum();

        // Get all other files
        $others = FileManagerFile::where('user_id', $this->id)
            ->whereNotIn('mimetype', $document_mimetypes)
            ->whereNotIn('type', ['audio', 'video', 'image'])
            ->get()->map(function ($item) {
                return (int)$item->getRawOriginal('filesize');
            })->sum();

        return [
            'data' => [
                'id'            => (string)$this->id,
                'type'          => 'storage',
                'attributes'    => [
                    'used'       => Metric::bytes($this->used_capacity)->format(),
                    'capacity'   => format_gigabytes($this->settings->storage_capacity),
                    'percentage' => (float)get_storage_fill_percentage($this->used_capacity, $this->settings->storage_capacity),
                ],
                'meta' => [
                    'images'    => [
                        'used'       => Metric::bytes($images)->format(),
                        'percentage' => (float)get_storage_fill_percentage($images, $this->settings->storage_capacity),
                    ],
                    'audios'    => [
                        'used'       => Metric::bytes($audios)->format(),
                        'percentage' => (float)get_storage_fill_percentage($audios, $this->settings->storage_capacity),
                    ],
                    'videos'    => [
                        'used'       => Metric::bytes($videos)->format(),
                        'percentage' => (float)get_storage_fill_percentage($videos, $this->settings->storage_capacity),
                    ],
                    'documents' => [
                        'used'       => Metric::bytes($documents)->format(),
                        'percentage' => (float)get_storage_fill_percentage($documents, $this->settings->storage_capacity),
                    ],
                    'others'    => [
                        'used'       => Metric::bytes($others)->format(),
                        'percentage' => (float)get_storage_fill_percentage($others, $this->settings->storage_capacity),
                    ],
                ]
            ]
        ];
    }
}
