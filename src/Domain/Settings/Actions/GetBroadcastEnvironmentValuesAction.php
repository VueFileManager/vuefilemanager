<?php
namespace Domain\Settings\Actions;

use Illuminate\Support\Str;

class GetBroadcastEnvironmentValuesAction
{
    /**
     * Get environment variables for the requested broadcast driver.
     *
     * Drivers:
     *  - "pusher" hosted Pusher service
     *  - "native" self-hosted Laravel Reverb websocket server (Pusher protocol compatible)
     *  - "none"   broadcasting disabled
     */
    public function __invoke(string $driver, array $credentials): array
    {
        return match ($driver) {
            'pusher' => [
                'BROADCAST_CONNECTION' => 'pusher',
                'PUSHER_APP_ID'        => $credentials['id'] ?? '',
                'PUSHER_APP_KEY'       => $credentials['key'] ?? '',
                'PUSHER_APP_SECRET'    => $credentials['secret'] ?? '',
                'PUSHER_APP_CLUSTER'   => $credentials['cluster'] ?? '',
                'PUSHER_APP_HOST'      => '',
                'PUSHER_APP_PORT'      => '',
                'PUSHER_APP_TLS'       => 'true',
            ],
            'native', 'reverb' => $this->reverb($credentials),
            default => [
                'BROADCAST_CONNECTION' => 'null',
            ],
        };
    }

    private function reverb(array $credentials): array
    {
        $tls = (bool) ($credentials['tls'] ?? false);

        return [
            'BROADCAST_CONNECTION' => 'reverb',
            // Use credentials from request, otherwise keep already generated credentials, or generate new ones.
            // They must match the credentials of the running Reverb server (php artisan websockets:install).
            'REVERB_APP_ID'        => ($credentials['id'] ?? null) ?: (config('broadcasting.connections.reverb.app_id') ?: (string) random_int(100000, 999999)),
            'REVERB_APP_KEY'       => ($credentials['key'] ?? null) ?: (config('broadcasting.connections.reverb.key') ?: Str::lower(Str::random(20))),
            'REVERB_APP_SECRET'    => ($credentials['secret'] ?? null) ?: (config('broadcasting.connections.reverb.secret') ?: Str::lower(Str::random(32))),
            'REVERB_HOST'          => $credentials['host'] ?? '',
            'REVERB_PORT'          => ($credentials['port'] ?? null) ?: ($tls ? '443' : '80'),
            'REVERB_SCHEME'        => $tls ? 'https' : 'http',
        ];
    }
}
