<?php

use App\Setting;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $columns = collect([
            [
                'name'  => 'section_features',
                'value' => '1',
            ],
            [
                'name'  => 'section_feature_boxes',
                'value' => '1',
            ],
            [
                'name'  => 'section_pricing_content',
                'value' => '1',
            ],
            [
                'name'  => 'section_get_started',
                'value' => '1',
            ],
            [
                'name'  => 'header_title',
                'value' => 'Simple <span style="color: #41B883">&</span> Powerful Personal Cloud Storage',
            ],
            [
                'name'  => 'header_description',
                'value' => 'Your private cloud storage software build on Laravel & Vue.js. No limits & no monthly fees. Truly freedom.',
            ],
            [
                'name'  => 'features_title',
                'value' => 'The Fastest Growing <span style="color: #41B883">File Manager</span> on the CodeCanyon Market',
            ],
            [
                'name'  => 'features_description',
                'value' => 'Your private cloud storage software build on Laravel & Vue.js. No limits & no monthly fees. Truly freedom.',
            ],
            [
                'name'  => 'feature_title_1',
                'value' => 'Truly Freedom',
            ],
            [
                'name'  => 'feature_description_1',
                'value' => 'You have full control over VueFileManager, no third authorities will control your service or usage, only you.',
            ],
            [
                'name'  => 'feature_title_2',
                'value' => 'The Sky is the Limit',
            ],
            [
                'name'  => 'feature_description_2',
                'value' => 'VueFileManager is cloud storage software. You have to install and running application on your own server hosting.',
            ],
            [
                'name'  => 'feature_title_3',
                'value' => 'No Monthly Fees',
            ],
            [
                'name'  => 'feature_description_3',
                'value' => 'When you running VueFileManager on your own server hosting, anybody can\'t control your content or resell your user data. Your data is safe.',
            ],
            [
                'name'  => 'pricing_title',
                'value' => 'Pick the <span style="color: #41B883;">Best Plan</span> For Your Needs',
            ],
            [
                'name'  => 'pricing_description',
                'value' => 'Your private cloud storage software build on Laravel & Vue.js. No limits & no monthly fees. Truly freedom.',
            ],
            [
                'name'  => 'get_started_title',
                'value' => 'Ready to Get <span style="color: #41B883">Started</span><br> With Us?',
            ],
            [
                'name'  => 'get_started_description',
                'value' => 'Your private cloud storage software build on Laravel & Vue.js. No limits & no monthly fees. Truly freedom.',
            ],
            [
                'name'  => 'footer_content',
                'value' => '© 2020 Simple & Powerful Personal Cloud Storage. Developed by <a href="https://hi5ve.digital" target="_blank">Hi5Ve.Digital</a>',
            ],
        ]);

        $columns->each(function ($content) {
            Setting::updateOrCreate($content);
        });
    }
}
