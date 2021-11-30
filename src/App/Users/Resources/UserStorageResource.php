<?php
namespace App\Users\Resources;

use ByteUnits\Metric;
use Illuminate\Support\Facades\DB;
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
        list($images, $audios, $videos, $documents, $others) = $this->get_file_type_distribution();

        list($downloadTotal, $uploadTotal, $upload, $download) = $this->get_traffic_distribution();

        return [
            'data' => [
                'id'         => (string) $this->id,
                'type'       => 'storage',
                'attributes' => [
                    'used'       => Metric::bytes($this->usedCapacity)->format(),
                    'capacity'   => format_gigabytes($this->limitations->max_storage_amount),
                    'percentage' => (float) get_storage_fill_percentage($this->usedCapacity, $this->limitations->max_storage_amount),
                ],
                'meta'       => [
                    'traffic'   => [
                        'chart'    => [
                            'download' => $download,
                            'upload'   => $upload,
                        ],
                        'download' => Metric::bytes($downloadTotal)->format(),
                        'upload'   => Metric::bytes($uploadTotal)->format(),
                    ],
                    'images'    => [
                        'used'       => Metric::bytes($images)->format(),
                        'percentage' => (float) get_storage_fill_percentage($images, $this->limitations->max_storage_amount),
                    ],
                    'audios'    => [
                        'used'       => Metric::bytes($audios)->format(),
                        'percentage' => (float) get_storage_fill_percentage($audios, $this->limitations->max_storage_amount),
                    ],
                    'videos'    => [
                        'used'       => Metric::bytes($videos)->format(),
                        'percentage' => (float) get_storage_fill_percentage($videos, $this->limitations->max_storage_amount),
                    ],
                    'documents' => [
                        'used'       => Metric::bytes($documents)->format(),
                        'percentage' => (float) get_storage_fill_percentage($documents, $this->limitations->max_storage_amount),
                    ],
                    'others'    => [
                        'used'       => Metric::bytes($others)->format(),
                        'percentage' => (float) get_storage_fill_percentage($others, $this->limitations->max_storage_amount),
                    ],
                ],
            ],
        ];
    }

    private function get_file_type_distribution(): array
    {
        $document_mimetypes = [
            'pdf', 'numbers', 'xlsx', 'xls', 'txt', 'md', 'rtf', 'pptx', 'ppt', 'odt', 'ods', 'odp', 'epub', 'docx', 'doc', 'csv', 'pages',
        ];

        $images = DB::table('files')
            ->where('user_id', $this->id)
            ->where('type', 'image')
            ->sum('filesize');

        $audios = DB::table('files')
            ->where('user_id', $this->id)
            ->where('type', 'audio')
            ->sum('filesize');

        $videos = DB::table('files')
            ->where('user_id', $this->id)
            ->where('type', 'video')
            ->sum('filesize');

        $documents = DB::table('files')
            ->where('user_id', $this->id)
            ->whereIn('mimetype', $document_mimetypes)
            ->sum('filesize');

        $others = DB::table('files')
            ->where('user_id', $this->id)
            ->whereNotIn('mimetype', $document_mimetypes)
            ->whereNotIn('type', ['audio', 'video', 'image'])
            ->sum('filesize');

        return [$images, $audios, $videos, $documents, $others];
    }

    private function get_traffic_distribution(): array
    {
        $period = now()->subDays(45)->endOfDay();

        $uploadMax = \DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->max('upload');

        $downloadMax = \DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->max('download');

        $trafficRecords = \DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->get();

        $downloadTotal = \DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->sum('download');

        $uploadTotal = \DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->sum('upload');

        $upload = $trafficRecords->map(fn ($record)   => round(($record->upload / $uploadMax) * 100, 2));
        $download = $trafficRecords->map(fn ($record) => round(($record->download / $downloadMax) * 100, 2));

        return [$downloadTotal, $uploadTotal, $upload, $download];
    }
}
