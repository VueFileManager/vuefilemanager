<?php

use App\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
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
                'visibility' => 1,
                'title'      => 'Terms of Service',
                'slug'       => 'terms-of-service',
                'content'    => 'Laoreet cum hendrerit iaculis arcu phasellus congue et elementum, pharetra risus imperdiet aptent posuere rutrum parturient blandit, dapibus tellus ridiculus potenti aliquam sociis turpis. Nullam commodo eget laoreet risus cursus vel placerat, in dapibus sociis gravida faucibus sodales, fringilla potenti elit semper iaculis ullamcorper. Dignissim vulputate pretium montes pellentesque mollis, consectetur adipiscing curabitur semper sem rhoncus, litora viverra curae proin.',
            ],
            [
                'visibility' => 1,
                'title'      => 'Privacy Policy',
                'slug'       => 'privacy-policy',
                'content'    => 'Sit orci justo augue maecenas laoreet consectetur natoque magnis in viverra sagittis, himenaeos urna facilisis mus proin primis diam accumsan tristique inceptos. Primis quisque posuere sit praesent lobortis feugiat semper convallis facilisis, vivamus gravida ligula nostra curae eu donec duis parturient senectus, arcu dolor viverra penatibus natoque cum nisi commodo. Litora sociis mauris justo nullam suspendisse mattis maecenas nascetur congue phasellus cras ultricies posuere donec, dapibus egestas diam lacus ornare montes senectus tincidunt eu taciti sed consequat.',
            ],
            [
                'visibility' => 1,
                'title'      => 'Cookie Policy',
                'slug'       => 'cookie-policy',
                'content'    => 'Metus penatibus ligula dolor natoque non habitasse laoreet facilisis, libero vivamus eget semper vulputate interdum integer, phasellus lorem enim blandit consectetur nullam sollicitudin. Hendrerit interdum luctus ut in molestie himenaeos eros cum laoreet parturient est, eu lectus hac et netus viverra dictumst congue elit sem senectus litora, fames scelerisque adipiscing inceptos fringilla montes sociosqu suscipit auctor potenti. Elementum lacus vulputate viverra ac morbi ligula ipsum facilisi, sit eu imperdiet lacinia congue dis vitae.',
            ],
        ]);

        $columns->each(function ($page) {
            Page::updateOrCreate($page);
        });
    }
}
