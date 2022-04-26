<?php
namespace Domain\Admin\Controllers\Dashboard;

use ByteUnits\Metric;
use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GetDashboardDataController extends Controller
{
    public function __invoke(): JsonResponse
    {
        // Get bandwidth data
        list($upload, $download, $uploadTotal, $downloadTotal, $storageUsage) = $this->getDiskData();

        return response()->json([
            'users' => [
                'total'             => User::count(),
            ],
            'disk'  => [
                'used'     => $storageUsage,
                'download' => [
                    'total'   => $downloadTotal,
                    'records' => $download,
                ],
                'upload'   => [
                    'total'   => $uploadTotal,
                    'records' => $upload,
                ],
            ],
            'app'   => [
                'isRunningCron'             => isRunningCron(),
                'license'                   => get_settings('license'),
                'version'                   => config('vuefilemanager.version'),
            ],
        ]);
    }

    private function getDiskData(): array
    {
        // Set period range for data retrieval
        $period = now()
            ->subDays(45)
            ->endOfDay();

        // Get bandwidth data
        $trafficRecords = DB::table('traffic')
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
            'percentage' => intval($trafficRecords->max('upload')) !== 0 ? round(($record->upload / $trafficRecords->max('upload')) * 100, 2) : 0,
            'amount'     => Metric::bytes($record->upload)->format(),
        ]);

        $download = $mappedTrafficRecords->map(fn ($record) => [
            'created_at' => $record->date,
            'percentage' => intval($trafficRecords->max('download')) !== 0 ? round(($record->download / $trafficRecords->max('download')) * 100, 2) : 0,
            'amount'     => Metric::bytes($record->download)->format(),
        ]);

        // Get total download/upload
        $downloadTotal = Metric::bytes(
            DB::table('traffic')
                ->where('created_at', '>', $period)
                ->sum('download')
        )->format();

        $uploadTotal = Metric::bytes(
            DB::table('traffic')
                ->where('created_at', '>', $period)
                ->sum('upload')
        )->format();

        // Get total storage usage
        $storageUsage = Metric::bytes(
            DB::table('files')->sum('filesize')
        )->format();

        return [$upload, $download, $uploadTotal, $downloadTotal, $storageUsage];
    }
}
