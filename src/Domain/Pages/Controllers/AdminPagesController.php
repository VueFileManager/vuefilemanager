<?php
namespace Domain\Pages\Controllers;

use Illuminate\Http\Request;
use Domain\Pages\Models\Page;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Pages\Resources\PageResource;
use Domain\Pages\Resources\PageCollection;

class AdminPagesController extends Controller
{
    /**
     * Get all pages
     */
    public function index(): PageCollection
    {
        return new PageCollection(
            Page::sortable()
                ->paginate(10)
        );
    }

    /**
     * Get single page resource
     */
    public function show(Page $page): PageResource
    {
        return new PageResource($page);
    }

    /**
     * Update page content
     */
    public function update(Request $request, Page $page): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        $page->update(
            make_single_input($request)
        );

        return response(
            new PageResource($page),
            204
        );
    }
}
