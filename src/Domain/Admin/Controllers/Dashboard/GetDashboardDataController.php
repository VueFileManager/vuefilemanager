<?php

namespace Domain\Admin\Controllers\Dashboard;

use ByteUnits\Metric;
use App\Users\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class GetDashboardDataController extends Controller
{
    public function __invoke(): Application|ResponseFactory|Response
    {
        // Get bandwidth data
        list($upload, $download, $uploadTotal, $downloadTotal, $storageUsage) = $this->getDiskData();

        // Get total earnings from transactions
        $totalEarnings = DB::table('transactions')
            ->where('status', 'completed')
            ->where('type', 'charge')
            ->sum('amount');

        return response([
            'users' => [
                'total'             => User::count(),
                'usersPremiumTotal' => Subscription::count(),
            ],
            'disk'  => [
                'used'          => $storageUsage,
                'download'      => [
                    'total'   => $downloadTotal,
                    'records' => $download,
                ],
                'upload'        => [
                    'total'   => $uploadTotal,
                    'records' => $upload,
                ],
            ],
            'app'   => [
                'license'  => get_settings('license'),
                'version'  => config('vuefilemanager.version'),
                'earnings' => format_currency($totalEarnings, 'USD'), // todo: refactor currency to global setup
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
            ->select(
                DB::raw('DATE(created_at) as created_at'),
                DB::raw('sum(download) as download'),
                DB::raw('sum(upload) as upload'))
            ->groupBy('created_at')
            ->get();

        $upload = $trafficRecords->map(fn($record) => [
            'created_at' => format_date($record->created_at, '%d. %B. %Y'),
            'amount'     => intval($trafficRecords->max('upload')) !== 0 ? round(($record->upload / $trafficRecords->max('upload')) * 100, 2) : 0,
        ]);

        $download = $trafficRecords->map(fn($record) => [
            'created_at' => format_date($record->created_at, '%d. %B. %Y'),
            'amount'     => intval($trafficRecords->max('download')) !== 0 ? round(($record->download / $trafficRecords->max('download')) * 100, 2) : 0,
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
