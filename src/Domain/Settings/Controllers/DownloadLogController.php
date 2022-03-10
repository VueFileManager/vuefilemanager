<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadLogController extends Controller
{
    public function __invoke($log): Response|BinaryFileResponse|Application|ResponseFactory
    {
        if (is_demo()) {
            return response('Done.', 204);
        }

        // Get log path
        $logPath = storage_path("logs/$log");

        // Download log
        return response()->download(
            storage_path("logs/$log"),
            $log,
            [
                'Accept-Ranges'       => 'bytes',
                'Content-Type'        => 'text/plain',
                'Content-Length'      => File::size($logPath),
                'Content-Range'       => 'bytes 0-600/' . File::size($logPath),
                'Content-Disposition' => "attachment; filename=$log",
            ]
        );
    }
}
