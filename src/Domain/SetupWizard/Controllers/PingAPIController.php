<?php
namespace Domain\SetupWizard\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class PingAPIController extends Controller
{
    public function __invoke(): Response
    {
        return response('pong');
    }
}
