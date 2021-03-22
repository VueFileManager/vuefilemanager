<?php

namespace Tests\Feature\Oasis;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OasisAdminTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_get_register_by_ico()
    {
        $response = [
            "name"          => "GDPR Cloud Solution, s.r.o.",
            "ico"           => "08995281",
            "city"          => "Praha",
            "addr_city"     => "Praha 5",
            "addr_zip"      => "15900",
            "addr_streetnr" => "Zbraslavská 12/11",
            "addr_full"     => "Zbraslavská 12/11, Malá Chuchle, 159 00 Praha 5",
        ];

        Http::fake([
            'https://or.justice.cz/ias/ui/rejstrik-08995281' => Http::response($response, 200),
        ]);

        $this->getJson('/api/oasis/admin/company-details?ico=08995281')
            ->assertStatus(200)
            ->assertExactJson($response);
    }

    /**
     * @test
     */
    public function it_try_to_get_register_by_ico_with_not_found_result()
    {
        Http::fake([
            'https://or.justice.cz/ias/ui/rejstrik-0899528199' => Http::response([], 200),
        ]);

        $this->getJson('/api/oasis/admin/company-details?ico=0899528199')
            ->assertNotFound();
    }
}
