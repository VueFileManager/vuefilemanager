<?php

namespace Tests\Feature\Oasis;

use App\Models\Oasis\InvoiceProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OasisInvoiceProfileTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function user_store_invoice_profile_with_images()
    {
        Storage::fake('local');

        $image = UploadedFile::fake()
            ->image('fake-image.jpg');

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices/profile', [
            'logo'               => $image,
            'stamp'              => $image,
            'company'            => 'VueFileManager Inc.',
            'email'              => 'howdy@hi5ve.digital',
            'ico'                => '11111111',
            'dic'                => '11111111',
            'ic_dph'             => 'SK20002313123',
            'registration_notes' => 'Some registration notes',
            'author'             => 'John Doe',
            'address'            => 'Does 11',
            'state'              => 'Slovakia',
            'city'               => 'Bratislava',
            'postal_code'        => '04001',
            'country'            => 'SK',
            'phone'              => '+421950123456',
            'bank'               => 'Fio Banka',
            'iban'               => 'SK20000054236423624',
            'swift'              => 'FIOZXXX',
        ])->assertStatus(201);

        $this->assertDatabaseHas('invoice_profiles', [
            'user_id' => $user->id,
            'company' => 'VueFileManager Inc.',
            'email'   => 'howdy@hi5ve.digital',
        ]);

        $profile = InvoiceProfile::first();

        Storage::disk('local')->assertExists($profile->logo);
        Storage::disk('local')->assertExists($profile->stamp);
    }
}
