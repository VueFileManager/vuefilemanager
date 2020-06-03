<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Plans')->insert([
            'name'        => 'Starter Pack',
            'description' => 'Faucibus massa amet fermentum sodales natoque mauris',
            'price'       => '9.90',
            'capacity'    => '200',
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);

        DB::table('Plans')->insert([
            'name'        => 'Professional Pack',
            'description' => 'Fusce morbi a massa ullamcorper inceptos fermentum',
            'price'       => '19.90',
            'capacity'    => '500',
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);

        DB::table('Plans')->insert([
            'name'        => 'Business Pack',
            'description' => 'Taciti metus proin sociis aenean facilisis eu',
            'price'       => '44.90',
            'capacity'    => '1000',
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);
    }
}
