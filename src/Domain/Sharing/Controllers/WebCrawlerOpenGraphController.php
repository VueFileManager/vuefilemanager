<?php
namespace Domain\Sharing\Controllers;

use Illuminate\View\View;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;

class WebCrawlerOpenGraphController extends Controller
{
    /**
     * Get og site for web crawlers
     */
    public function __invoke(
        Share $shared
    ): View {
        $namespace = match ($shared->type) {
            'folder' => 'Domain\\Folders\\Models\\Folder',
            'file'   => 'Domain\\Files\\Models\\File',
        };

        // Get file/folder record
        $item = ($namespace)::where('user_id', $shared->user->id)
            ->where('id', $shared->item_id)
            ->first();

        if ($item->thumbnail) {
            $item->setPublicUrl($shared->token);
        }

        return view('vuefilemanager.crawler.og-view')
            ->with('settings', get_settings_in_json())
            ->with('metadata', [
                'url'          => url('/share', ['token' => $shared->token]),
                'is_protected' => $shared->is_protected,
                'user'         => $shared->user->settings->name,
                'name'         => $item->name,
                'size'         => $shared->type === 'folder'
                    ? $item->items
                    : $item->filesize,
                'thumbnail' => $item->thumbnail ?? null,
            ]);
    }
}
