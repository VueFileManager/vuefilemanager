<?php
namespace Domain\Pages\Actions;

use Domain\Pages\Models\Page;

class SeedDefaultPagesAction
{
    /**
     * Store default pages content like Terms of Service, Privacy Policy and Cookie Policy into database
     */
    public function __invoke(): void
    {
        collect(config('content.pages'))
            ->each(fn ($page) => Page::updateOrCreate($page));
    }
}
