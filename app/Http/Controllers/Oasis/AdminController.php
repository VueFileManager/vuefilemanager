<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Services\CzechRegisterSearchService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get company details from czech company register
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get_company_details()
    {
        $api = resolve(CzechRegisterSearchService::class);

        $result = $api->findByIco(
            request()->get('ico')
        );

        if (empty($result)) {
            return response('Not Found', 404);
        }

        return response($result[0], 200);
    }
}
