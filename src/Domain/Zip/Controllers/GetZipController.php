<?php
namespace Domain\Zip\Controllers;

use Domain\Zip\Models\Zip;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Domain\Traffic\Actions\RecordDownloadAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetZipController extends Controller
{
    public function __construct(
        private RecordDownloadAction $recordDownload,
    ) {
    }

    /**
     * Get generated zip for user
     */
    public function __invoke(
        string $id,
    ): StreamedResponse {
        $disk = Storage::disk('local');

        $zip = Zip::whereId($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Store user download size
        ($this->recordDownload)(
            file_size: $disk->size("zip/$zip->basename"),
            user_id: $zip->user_id,
        );

        return $disk->download("zip/$zip->basename", $zip->basename, [
            'Content-Type'        => 'application/zip',
            'Content-Length'      => $disk->size("zip/$zip->basename"),
            'Accept-Ranges'       => 'bytes',
            'Content-Range'       => 'bytes 0-600/' . $disk->size("zip/$zip->basename"),
            'Content-Disposition' => "attachment; filename=$zip->basename",
        ]);
    }
}
