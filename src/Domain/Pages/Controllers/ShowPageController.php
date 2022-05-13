<?php
namespace Domain\Pages\Controllers;

use Domain\Pages\Models\Page;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Pages\Resources\PageResource;

class ShowPageController extends Controller
{
    /**
     * Get single page content
     */
    public function __invoke(Page $page): JsonResponse
    {
        return response()->json(new PageResource($page));
    }
}
