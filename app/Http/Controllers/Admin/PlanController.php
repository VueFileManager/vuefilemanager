<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function create(Request $request)
    {
        return $request->all();
    }

    public function update(Request $request)
    {
        return $request->all();
    }
}
