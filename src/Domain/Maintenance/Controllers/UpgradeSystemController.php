<?php
namespace Domain\Maintenance\Controllers;

use Artisan;
use DB;
use Schema;
use Storage;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\Maintenance\Models\AppUpdate;
use Domain\Maintenance\Actions\UpgradeDatabaseAction;

class UpgradeSystemController extends Controller
{
    public function __construct(
        public UpgradeDatabaseAction $upgradeDatabase,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        ini_set('max_execution_time', -1);

        // Clear config
        Artisan::call('config:clear');

        // Get already updated versions
        $alreadyUpdated = Schema::hasTable('app_updates')
            ? AppUpdate::all()
                ->pluck('version')
                ->toArray()
            : [];

        // Get versions which has to be upgraded
        $needToUpgrade = array_diff(config('vuefilemanager.updates'), $alreadyUpdated);

        // Iterate and upgrade
        foreach ($needToUpgrade as $version) {
            // Get method name
            $method = "upgrade_to_$version";

            if (method_exists($this, $method)) {
                // Run update
                $this->{$method}($request);

                // Store update record
                AppUpdate::create(['version' => $version]);

                return response('Done', 201);
            }
        }

        return response('Whooops, something went wrong!', 500);
    }

    private function upgrade_to_2_0_10(): void
    {
        ($this->upgradeDatabase)();

        // Upgrade team folder content ownership
        Folder::where('parent_id', null)
            ->where('team_folder', true)
            ->cursor()
            ->each(function ($teamFolder) {
                // Get all inherited folder from team folder
                $childrenFolderIds = Folder::with('folders:id,parent_id')
                    ->where('id', $teamFolder->id)
                    ->get('id');

                $teamFolderIds = Arr::flatten(filter_folders_ids($childrenFolderIds));

                // Replace user content ownership for author of team folder
                DB::table('files')
                    ->whereIn('parent_id', $teamFolderIds)
                    ->cursor()
                    ->each(function ($file) use ($teamFolder) {
                        // Move image thumbnails
                        if ($file->type === 'image') {
                            // Get image thumbnail list
                            $thumbnailList = getThumbnailFileList($file->basename);

                            // move thumbnails to the new location
                            $thumbnailList->each(function ($basename) use ($file, $teamFolder) {
                                $oldPath = "files/$file->user_id/$basename";
                                $newPath = "files/$teamFolder->user_id/$basename";

                                if (Storage::exists($oldPath)) {
                                    Storage::move($oldPath, $newPath);
                                }
                            });
                        }

                        // Get single file path
                        $filePath = "files/$file->user_id/$file->basename";

                        // Move single file
                        if (Storage::exists($filePath)) {
                            Storage::move($filePath, "files/$teamFolder->user_id/$file->basename");
                        }

                        // Update file permission
                        File::find($file->id)->update([
                            'user_id'     => $teamFolder->user_id,
                            'creator_id'  => $teamFolder->user_id !== $file->user_id ? $file->user_id : null,
                        ]);
                    });

                // Update folder ownership
                DB::table('folders')
                    ->whereIn('parent_id', $teamFolderIds)
                    ->update(['user_id' => $teamFolder->user_id]);
            });

        // Upgrade dwg files
        File::withTrashed()
            ->where('mimetype', 'vnd.dwg')
            ->cursor()
            ->each(fn ($file) => $file->update([
                'mimetype' => 'dwg',
                'type'     => 'file',
            ]));
    }
}
