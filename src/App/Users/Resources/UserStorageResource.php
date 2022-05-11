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

        $totalCapacity = match (get_settings('subscription_type')) {
            'metered' => $this->usedCapacity / 1000000000,
            'fixed'   => $this->limitations->max_storage_amount,
            default   => $this->limitations->max_storage_amount,
        };

        return [
            'data' => [
                'id'         => (string) $this->id,
                'type'       => 'storage',
                'attributes' => [
                    'used'       => Metric::bytes($this->usedCapacity)->format(),
                    'capacity'   => toGigabytes($totalCapacity),
                    'percentage' => (float) get_storage_percentage($this->usedCapacity, $totalCapacity),
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
                        'percentage' => (float) get_storage_percentage($images, $totalCapacity),
                    ],
                    'audios'    => [
                        'used'       => Metric::bytes($audios)->format(),
                        'percentage' => (float) get_storage_percentage($audios, $totalCapacity),
                    ],
                    'videos'    => [
                        'used'       => Metric::bytes($videos)->format(),
                        'percentage' => (float) get_storage_percentage($videos, $totalCapacity),
                    ],
                    'documents' => [
                        'used'       => Metric::bytes($documents)->format(),
                        'percentage' => (float) get_storage_percentage($documents, $totalCapacity),
                    ],
                    'others'    => [
                        'used'       => Metric::bytes($others)->format(),
                        'percentage' => (float) get_storage_percentage($others, $totalCapacity),
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

        $uploadMax = DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->max('upload');

        $downloadMax = DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->max('download');

        $downloadTotal = DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->sum('download');

        $uploadTotal = DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->sum('upload');

        $trafficRecords = DB::table('traffic')
            ->where('user_id', $this->id)
            ->where('created_at', '>', $period)
            ->groupBy('date')
            ->get([
                DB::raw('Date(created_at) as date'),
                DB::raw('sum(download) as download'),
                DB::raw('sum(upload) as upload'),
            ])
            ->each(fn ($record) => $record->date = format_date($record->date, 'd. M. Y'))
            ->keyBy('date');

        $mappedTrafficRecords = mapTrafficRecords($trafficRecords);

        $upload = $mappedTrafficRecords->map(fn ($record) => [
            'created_at' => $record->date,
            'amount'     => Metric::bytes($record->upload)->format(),
            'percentage' => intval($uploadMax) !== 0
                ? round(($record->upload / $uploadMax) * 100, 2)
                : 0,
        ]);

        $download = $mappedTrafficRecords->map(fn ($record) => [
            'created_at' => $record->date,
            'amount'     => Metric::bytes($record->download)->format(),
            'percentage' => intval($downloadMax) !== 0
                ? round(($record->download / $downloadMax) * 100, 2)
                : 0,
        ]);

        return [$downloadTotal, $uploadTotal, $upload, $download];
    }
}
