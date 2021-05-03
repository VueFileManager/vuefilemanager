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

        $this->create_demo_content();
        $this->set_oasis_data();

        $this->info('Dispatching jobs...');
        $this->call('queue:work', [
            '--stop-when-empty' => true,
        ]);

        $this->info('Everything is done, congratulations! 🥳🥳🥳');
    }

    public function create_demo_content()
    {
        $user = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        $hash = Str::random(12);

        // Get invoice logo and stamp
        Storage::putFileAs('system', storage_path('demo/app/logo-horizontal.svg'), "{$hash}-logo-horizontal.svg", 'private');
        Storage::putFileAs('system', storage_path('demo/oasis/stamp.png'), "{$hash}-stamp.png", 'private');

        $profile = $user->invoiceProfile()->create([
            'company' => 'VueFileManager Inc.',
            'registration_notes' => 'Registrácia na OR SR Bratislava I. oddiel: Sro vl. č. 91906',
            'logo' => "system/{$hash}-logo-horizontal.svg",
            'ico' => '46530045',
            'dic' => '2023489457',
            'ic_dph' => 'SK2023489457',
            'address' => 'Does 11',
            'state' => 'Slovakia',
            'city' => 'Bratislava',
            'postal_code' => '04001',
            'country' => 'SK',
            'bank' => 'Fio Banka',
            'iban' => 'SK20000054236423624',
            'swift' => 'FIOZXXX',
            'phone' => '+421950123456',
            'email' => 'howdy@hi5ve.digital',
            'author' => 'John Doe',
            'stamp' => "system/{$hash}-stamp.png",
        ]);

        $clients = Client::factory(Client::class)
            ->count(6)
            ->create(['user_id' => $user->id]);

        $regular_invoices = Invoice::factory(Invoice::class)
            ->state(new Sequence(
                ['client_id' => $clients[0]->id],
                ['client_id' => $clients[1]->id],
                ['client_id' => $clients[2]->id],
                ['client_id' => $clients[3]->id],
                ['client_id' => $clients[4]->id],
                ['client_id' => $clients[5]->id],
            ))->count(2)
            ->create([
                'user_id' => $user->id,
                'invoice_type' => 'regular-invoice',
                'discount_type' => null,
                'user' => $profile->toArray(),
            ]);

        $advance_invoices = Invoice::factory(Invoice::class)
            ->count(2)
            ->state(new Sequence(
                ['client_id' => $clients[0]->id],
                ['client_id' => $clients[1]->id],
                ['client_id' => $clients[2]->id],
                ['client_id' => $clients[3]->id],
                ['client_id' => $clients[4]->id],
                ['client_id' => $clients[5]->id],
            ))->create([
                'user_id' => $user->id,
                'invoice_type' => 'advance-invoice',
                'discount_type' => null,
                'user' => $profile->toArray(),
            ]);

        // Generate PDF
        collect([$regular_invoices, $advance_invoices])
            ->collapse()
            ->each(function ($invoice) use ($user) {
                $this->info("Generating invoice id: $invoice->id");

                \PDF::loadView('oasis.invoices.invoice', [
                    'invoice' => Invoice::find($invoice->id),
                    'user' => $user,
                ])
                    ->setPaper('a4')
                    ->setOrientation('portrait')
                    ->save(
                        storage_path("app/files/{$invoice->user_id}/invoice-{$invoice->id}.pdf")
                    );
            });
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
