<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $columns = collect([

            // Service Billing Info
            ['name' => 'billing_phone_number'],
            ['name' => 'billing_postal_code'],
            ['name' => 'billing_vat_number'],
            ['name' => 'billing_address'],
            ['name' => 'billing_country'],
            ['name' => 'billing_state'],
            ['name' => 'billing_city'],
            ['name' => 'billing_name'],

            // General Settings
            ['name' => 'app_title'],
            ['name' => 'app_description'],

            ['name' => 'app_logo'],
            ['name' => 'app_favicon'],

            ['name' => 'google_analytics'],
            ['name' => 'contact_email'],

            // Users
            ['name' => 'registration', 'value' => 1],
            ['name' => 'storage_limitation', 'value' => 1],
            ['name' => 'storage_default', 'value' => 1],

            // Mail settings
            ['name' => 'mail_driver'],
            ['name' => 'mail_host'],
            ['name' => 'mail_port'],
            ['name' => 'mail_username'],
            ['name' => 'mail_password'],
            ['name' => 'mail_encryption'],
        ]);

        $columns->each(function ($col) {
            DB::table('settings')->insert([
                'name'  => $col['name'],
                'value' => isset($col['value']) ? $col['value'] : null,
            ]);
        });
    }
}
