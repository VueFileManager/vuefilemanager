<?php
namespace Domain\Sharing\Controllers;

use BaconQrCode\Writer;
use Illuminate\Http\JsonResponse;
use BaconQrCode\Renderer\Color\Rgb;
use App\Http\Controllers\Controller;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class GetShareLinkViaQrCodeController extends Controller
{
    public function __invoke(
        $token
    ): JsonResponse {
        // Get share url
        $url = url('/share', ['token' => $token]);

        // Generate qr code
        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(192, 2, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(0, 0, 0))),
                new SvgImageBackEnd
            )
        ))->writeString($url);

        $qrCode = trim(substr($svg, strpos($svg, "\n") + 1));

        // Return qr code
        return response()->json([
            'type'    => 'success',
            'message' => 'QR code successfully generated',
            'data'    => [
                'svg' => $qrCode,
            ],
        ]);
    }
}
