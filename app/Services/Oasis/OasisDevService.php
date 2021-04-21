<?php


namespace App\Services\Oasis;


use App\Models\Oasis\Client;
use App\Models\Oasis\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

class OasisDevService
{
    public function create_demo_content()
    {
        $user = User::whereEmail('howdy@hi5ve.digital')
            ->first();

        $clients = Client::factory(Client::class)
            ->count(6)
            ->create(['user_id' => $user->id]);

        Invoice::factory(Invoice::class)
            ->state(new Sequence(
                ['client_id' => $clients[0]->id],
                ['client_id' => $clients[1]->id],
                ['client_id' => $clients[2]->id],
                ['client_id' => $clients[3]->id],
                ['client_id' => $clients[4]->id],
                ['client_id' => $clients[5]->id],
            ))->count(14)
            ->create([
                'user_id'      => $user->id,
                'invoice_type' => 'regular_invoice'
            ]);

        Invoice::factory(Invoice::class)
            ->count(14)
            ->state(new Sequence(
                ['client_id' => $clients[0]->id],
                ['client_id' => $clients[1]->id],
                ['client_id' => $clients[2]->id],
                ['client_id' => $clients[3]->id],
                ['client_id' => $clients[4]->id],
                ['client_id' => $clients[5]->id],
            ))->create([
                'user_id'      => $user->id,
                'invoice_type' => 'advance_invoice'
            ]);
    }
}