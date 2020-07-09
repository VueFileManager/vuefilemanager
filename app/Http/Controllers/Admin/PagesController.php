<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageCollection;
use App\Http\Resources\PageResource;
use App\Page;
use Illuminate\Http\Request;

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
            Page::all()
        );
    }

    /**
     * Get page resource
     *
     * @param $slug
     * @return PageResource
     */
    public function show($slug)
    {
        return new PageResource(
            Page::where('slug', $slug)->first()
        );
    }

    /**
     * Update page content
     *
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, $slug) {
        $page = Page::where('slug', $slug)->first();

        $page->update([
            $request->name => $request->value
        ]);

        return response('Done', 204);
    }
}
