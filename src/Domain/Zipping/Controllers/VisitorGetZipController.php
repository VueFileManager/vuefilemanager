<?php


namespace Domain\Zipping\Controllers;


use App\Http\Controllers\Controller;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Zipping\Models\Zip;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VisitorGetZipController extends Controller
{
    public function __construct(
        private RecordDownloadAction $recordDownload,
    ){}

    /**
     * Get generated zip for visitor
     */
    public function __invoke(
        $id,
        $token,
    ): StreamedResponse
    {
        $disk = Storage::disk('local');

        $zip = Zip::where('id', $id)
            ->where('shared_token', $token)
            ->first();

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
            'Content-Disposition' => 'attachment; filename=' . $zip->basename,
        ]);
    }
}