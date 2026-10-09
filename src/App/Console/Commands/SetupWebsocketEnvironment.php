<?php
namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class SetupWebsocketEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'websockets:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up Laravel Reverb websocket server production environment';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Get allowed origins
        $origins = $this->ask('Type the host of your VueFileManager front-end application to allow connection with your websocket server. If you want to accept multiple hosts, separate them with comma(,)');

        // Get Reverb application credentials, they must be the same in the VueFileManager application
        $appId = $this->ask('Reverb app ID', config('reverb.apps.apps.0.app_id') ?: (string) random_int(100000, 999999));
        $appKey = $this->ask('Reverb app key', config('reverb.apps.apps.0.key') ?: Str::lower(Str::random(20)));
        $appSecret = $this->ask('Reverb app secret', config('reverb.apps.apps.0.secret') ?: Str::lower(Str::random(32)));

        // Store origins to the .env file
        setEnvironmentValue([
            'BROADCAST_CONNECTION'   => 'reverb',
            'REVERB_ALLOWED_ORIGINS' => $origins,
            'REVERB_APP_ID'          => $appId,
            'REVERB_APP_KEY'         => $appKey,
            'REVERB_APP_SECRET'      => $appSecret,
            'APP_ENV'                => 'production',
            'APP_DEBUG'              => 'false',
        ]);

        $this->info('Your host/s and credentials was stored successfully.');

        $this->table(['Variable', 'Value'], [
            ['REVERB_APP_ID', $appId],
            ['REVERB_APP_KEY', $appKey],
            ['REVERB_APP_SECRET', $appSecret],
        ]);

        // Generate new app key
        if (! config('app.key')) {
            $this->call('key:generate', [
                '--force' => true,
            ]);
        }

        // Clear cache
        $this->call('config:clear');

        $this->info('Everything is done, congratulations! 🥳🥳🥳');
        $this->info('Start the websocket server with: php artisan reverb:start');
    }
}
