<?php
namespace Domain\Pages\Controllers;

use Domain\Pages\Models\Page;
use App\Http\Controllers\Controller;
use Domain\Pages\Resources\PageResource;

class PagesController extends Controller
{
    /**
     * Get single page content
     */
    public function show(Page $page): PageResource
    {
        return new PageResource($page);
    }
}
