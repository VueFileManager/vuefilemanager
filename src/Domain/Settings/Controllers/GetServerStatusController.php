<?php

namespace Domain\Settings\Controllers;

use Carbon\Carbon;
use Support\Status\Actions\GetServerSetupStatusAction;

class GetServerStatusController
{
    public function __construct(
        public GetServerSetupStatusAction $getServerSetupStatus,
    ) {}

    public function __invoke(): array
    {
        // Get server data
        $status = ($this->getServerSetupStatus)();

        // Add cron info
        $status['cron'] = [
            'running'    => isRunningCron(),
            'lastUpdate' => isRunningCron() ? format_date(cache()->get('latest_cron_update')) : '',
        ];

        return $status;
    }
}