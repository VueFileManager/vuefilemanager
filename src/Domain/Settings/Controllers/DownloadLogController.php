<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadLogController extends Controller
{
    public function __invoke($log): JsonResponse|BinaryFileResponse
    {
        if (is_demo()) {
            return response()->json([
                'type' => 'success',
            ]);
        }

        // Get log path
        $logPath = storage_path("logs/$log");

        // Download log
        return response()
            ->download(storage_path("logs/$log"), $log, [
                'Accept-Ranges'       => 'bytes',
                'Content-Type'        => 'text/plain',
                'Content-Length'      => File::size($logPath),
                'Content-Range'       => 'bytes 0-600/' . File::size($logPath),
                'Content-Disposition' => "attachment; filename=$log",
            ]);
    }
}
