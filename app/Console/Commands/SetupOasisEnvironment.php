<?php
namespace App\Console\Commands;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use App\Models\Oasis\Client;
use App\Models\Oasis\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Sequence;

class SetupOasisEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:oasis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Oasis demo content';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Setting up Oasis environment');

        $this->set_oasis_data();

        $this->info('Dispatching jobs...');
        $this->call('queue:work', [
            '--stop-when-empty' => true,
        ]);

        $this->info('Everything is done, congratulations! 🥳🥳🥳');
    }

    public function set_oasis_data()
    {
        Setting::updateOrCreate([
            'name' => 'app_color',
        ], [
            'value' => '#ae5fec',
        ]);

        // Get system images
        collect(['logo.png', 'logo-horizontal.png', 'favicon.png', 'oasis-og-image.jpg'])
            ->each(function ($file) {
                Storage::putFileAs('system', storage_path("demo/oasis/$file"), $file, 'private');
            });

        collect([
            [
                'name' => 'app_title',
                'value' => 'Oasis',
            ],
            [
                'name' => 'app_description',
                'value' => 'Chytrý, bezpečný, pohodlný šanon vždy s Vámi.',
            ],
            [
                'name' => 'app_logo',
                'value' => 'system/logo.png',
            ],
            [
                'name' => 'app_logo_horizontal',
                'value' => 'system/logo-horizontal.png',
            ],
            [
                'name' => 'app_favicon',
                'value' => 'system/favicon.png',
            ],
            [
                'name' => 'app_og_image',
                'value' => 'system/oasis-og-image.jpg',
            ],
        ])->each(function ($option) {
            Setting::updateOrCreate([
                'name' => $option['name'],
            ], [
                'value' => $option['value'],
            ]);
        });
    }
}
