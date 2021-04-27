<?php

namespace App\Console\Commands;

use App\Models\Oasis\Client;
use App\Models\Oasis\Invoice;
use App\Models\User;
use Illuminate\Console\Command;
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
                'user_id'      => $user->id,
                'invoice_type' => 'regular-invoice'
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
                'user_id'       => $user->id,
                'invoice_type'  => 'advance-invoice',
                'discount_type' => null,
            ]);

        // Generate PDF
        collect([$regular_invoices, $advance_invoices])
            ->collapse()
            ->each(function ($invoice) use ($user) {

                $this->info("Generating invoice id: $invoice->id");

                \PDF::loadView('oasis.invoices.invoice', [
                    'invoice' => Invoice::find($invoice->id),
                    'user'    => $user,
                ])
                    ->setPaper('a4')
                    ->setOrientation('portrait')
                    ->save(
                        storage_path("app/files/{$invoice->user_id}/invoice-{$invoice->id}.pdf")
                    );
            });
    }
}
