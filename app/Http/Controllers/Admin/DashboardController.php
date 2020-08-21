<?php

namespace App\Http\Controllers\Admin;

use App\FileManagerFile;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsersCollection;
use App\Services\StripeService;
use App\Setting;
use App\User;
use ByteUnits\Metric;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
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
        // Get total users
        $total_users = User::all()->count();

        // Get total used space
        $total_used_space = FileManagerFile::all()->map(function ($item) {
            return (int)$item->getRawOriginal('filesize');
        })->sum();

        // Get total premium users
        $total_premium_users = Subscription::where('stripe_status', 'active')->get()->count();

        // Get License
        $license = Setting::where('name', 'license')->first();

        return [
            'license'             => $license ? $license->value : null,
            'app_version'         => config('vuefilemanager.version'),
            'total_users'         => $total_users,
            'total_used_space'    => Metric::bytes($total_used_space)->format(),
            'total_premium_users' => $total_premium_users,
        ];
    }

    /**
     * Get the newest users
     *
     * @return UsersCollection
     */
    public function new_registrations()
    {
        return new UsersCollection(
            User::sortable(['created_at' => 'desc'])->paginate(10)
        );
    }
}
