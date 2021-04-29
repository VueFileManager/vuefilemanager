<?php
namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Services\DemoService;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Http\Resources\PageCollection;
use Illuminate\Contracts\Routing\ResponseFactory;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->demo = resolve(DemoService::class);
    }

    /**
     * Get all pages
     *
     * @return PageCollection
     */
    public function index()
    {
        return new PageCollection(
            Page::sortable()
                ->paginate(10)
        );
    }

    /**
     * Get single page resource
     *
     * @param $page
     * @return PageResource
     */
    public function show(Page $page)
    {
        return new PageResource($page);
    }

    /**
     * Update page content
     *
     * @param Request $request
     * @param Page $page
     * @return ResponseFactory|Response
     */
    public function update(Request $request, Page $page)
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        $page->update(
            make_single_input($request)
        );

        return response(new PageResource($page), 204);
    }
}
