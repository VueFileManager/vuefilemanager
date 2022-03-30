<?php
namespace Domain\Admin\Controllers\Dashboard;

use Schema;
use ByteUnits\Metric;
use App\Users\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Domain\Maintenance\Models\AppUpdate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class GetDashboardDataController extends Controller
{
    public function __invoke(): Application|ResponseFactory|Response
    {
        // Get app update data
        $shouldUpgrade = $this->getUpgradeData();

        // Get translations data
        list($originalTranslations, $activeTranslations) = $this->countTranslations();

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
                'shouldUpgrade'             => count($shouldUpgrade) > 0,
                'shouldUpgradeTranslations' => $activeTranslations !== $originalTranslations,
                'isRunningCron'             => isRunningCron(),
                'license'                   => get_settings('license'),
                'version'                   => config('vuefilemanager.version'),
                'earnings'                  => format_currency($totalEarnings, 'USD'), // todo: refactor currency to global setup or plan currency
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

    private function countTranslations(): array
    {
        $default_translations = [
            'extended' => collect([
                config('language-translations.extended'),
                config('language-translations.regular'),
                config('custom-language-translations'),
            ])->collapse(),
            'regular'  => collect([
                config('language-translations.regular'),
                config('custom-language-translations'),
            ])->collapse(),
        ];

        $originalTranslationCount = count($default_translations[get_settings('license')]);

        $activeTranslationsCount = DB::table('language_translations')
            ->where('lang', 'en')
            ->count();

        return [$originalTranslationCount, $activeTranslationsCount];
    }

    private function getUpgradeData(): array
    {
        // Get already updated versions
        $alreadyUpdated = Schema::hasTable('app_updates')
            ? AppUpdate::all()
                ->pluck('version')
                ->toArray()
            : [];

        // Get versions which has to be upgraded
        return array_diff(config('vuefilemanager.updates'), $alreadyUpdated);
    }
}
