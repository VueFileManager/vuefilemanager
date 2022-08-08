<?php
namespace Domain\Settings\Controllers;

use Storage;
use Illuminate\Http\JsonResponse;
use Support\Status\Actions\GetServerSetupStatusAction;

class GetServerStatusController
{
    public function __construct(
        public GetServerSetupStatusAction $getServerSetupStatus,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        // Get server data
        $status = ($this->getServerSetupStatus)();

        // Get latest logs
        $status['logs'] = getListOfLatestLogs();

        // Add latest database backups
        $status['backups'] = collect(Storage::allFiles('app-backup'))
            ->map(fn ($path) => str_replace('app-backup/', '', $path))
            ->reverse()
            ->values()
            ->take(5);

        // Add cron info
        $status['cron'] = [
            'running'    => isRunningCron(),
            'lastUpdate' => isRunningCron() ? format_date(cache()->get('latest_cron_update')) : null,
            'command'    => getCronCommandSuggestions(),
        ];

        return response()->json($status);
    }
}
