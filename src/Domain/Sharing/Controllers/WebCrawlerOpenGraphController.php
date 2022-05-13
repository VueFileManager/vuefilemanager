<?php
namespace Domain\Sharing\Controllers;

use Domain\Sharing\Models\Share;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class WebCrawlerOpenGraphController extends Controller
{
    /**
     * Get og site for web crawlers
     */
    public function __invoke(
        Share $share
    ): View {
        $namespace = match ($share->type) {
            'folder' => 'Domain\\Folders\\Models\\Folder',
            'file'   => 'Domain\\Files\\Models\\File',
        };

        // Get file/folder record
        $item = ($namespace)::where('user_id', $share->user_id)
            ->where('id', $share->item_id)
            ->first();

        if ($item->thumbnail) {
            $item->setSharedPublicUrl($share->token);
        }

        return view('vuefilemanager.crawler.og-view')
            ->with('settings', getAllSettings())
            ->with('metadata', [
                'url'          => url('/share', ['token' => $share->token]),
                'is_protected' => (int) $share->is_protected,
                'user'         => $share->user->settings->name,
                'name'         => $item->name,
                'size'         => $share->type === 'folder'
                    ? $item->items
                    : $item->filesize,
                'thumbnail' => $item->thumbnail ?? null,
            ]);
    }
}
