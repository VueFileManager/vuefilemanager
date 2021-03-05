<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageCollection;
use App\Http\Resources\PageResource;
use App\Http\Tools\Demo;
use App\Models\Page;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PagesController extends Controller
{
    /**
     * Get all pages
     *
     * @return PageCollection
     */
    public function index()
    {
        return new PageCollection(
            Page::sortable()->paginate(10)
        );
    }

    /**
     * Get page resource
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
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        $page->update(
            make_single_input($request)
        );

        return response(new PageResource($page), 204);
    }
}
