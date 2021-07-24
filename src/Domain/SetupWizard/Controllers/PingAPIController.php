<?php


namespace Domain\SetupWizard\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class PingAPIController extends Controller
{
    public function __invoke(): Response
    {
        return response('pong');
    }
}