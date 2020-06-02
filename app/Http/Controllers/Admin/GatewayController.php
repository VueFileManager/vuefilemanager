<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function update(Request $request, $gateway)
    {
        return $request->all();
    }
}
