<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsersCollection;
use App\Services\StripeService;
use App\Models\User;
use ByteUnits\Metric;
use DB;
use Laravel\Cashier\Subscription;

class DashboardController extends Controller
{
    /**
     * @param StripeService $stripe
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Get data for dashboard
     *
     * @return array
     */
    public function index()
    {
        // Get total premium users
        $premium_users = Subscription::whereStripeStatus('active')
            ->count();

        // Get total storage usage
        $storage_usage = Metric::bytes(
            DB::table('files')->sum('filesize')
        )->format();

        return [
            'license'             => get_setting('license'),
            'app_version'         => config('vuefilemanager.version'),
            'total_users'         => User::count(),
            'total_used_space'    => $storage_usage,
            'total_premium_users' => $premium_users,
        ];
    }

    /**
     * Get the newest users
     *
     * @return UsersCollection
     */
    public function newbies()
    {
        return new UsersCollection(
            User::sortable(['created_at' => 'desc'])
                ->paginate(10)
        );
    }
}
