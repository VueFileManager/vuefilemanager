<?php
namespace Domain\Homepage\Controllers;

use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;

class AppFunctionsController extends Controller
{
    /**
     * Get og site for web crawlers
     *
     * @param Share $shared
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function og_site(Share $shared)
    {
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
