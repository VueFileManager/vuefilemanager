<?php
namespace Domain\Items\Controllers;

use Exception;
use Illuminate\Http\Request;
use Domain\Settings\Models\File;
use Domain\Settings\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Requests\MoveItemRequest;
use Domain\Files\Requests\DeleteItemRequest;
use Domain\Files\Requests\RenameItemRequest;
use Domain\SetupWizard\Services\DemoService;
use Domain\Files\Requests\CreateFolderRequest;
use Domain\SetupWizard\Services\HelperService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\SetupWizard\Services\FileManagerService;

class EditItemsController extends Controller
{
    public function __construct(
        private FileManagerService $filemanager,
        private HelperService $helper,
        private DemoService $demo,
    ) {
    }

    /**
     * Create new folder for authenticated master|editor user
     *
     * @param CreateFolderRequest $request
     * @return Folder|array|Model
     * @throws Exception
     */
    public function create_folder(CreateFolderRequest $request)
    {
        if (is_demo_account('howdy@hi5ve.digital')) {
            return $this->demo->create_folder($request);
        }

        // Create new folder
        return $this->filemanager->create_folder($request);
    }

    /**
     * Rename item for authenticated master|editor user
     *
     * @param RenameItemRequest $request
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function rename_item(RenameItemRequest $request, $id)
    {
        if (is_demo_account('howdy@hi5ve.digital')) {
            return $this->demo->rename_item($request, $id);
        }

        // If request contain icon or color, then change it
        if ($request->filled('emoji') || $request->filled('color')) {
            $this->filemanager->edit_folder_properties($request, $id);
        }

        // Rename Item
        return $this->filemanager->rename_item($request, $id);
    }

    /**
     * Delete item for authenticated master|editor user
     *
     * @param DeleteItemRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function delete_item(DeleteItemRequest $request)
    {
        abort_if(is_demo_account('howdy@hi5ve.digital'), 204, 'Done.');

        foreach ($request->input('items') as $item) {
            $this->filemanager->delete_item($item, $item['id']);
        }

        return response('Done', 204);
    }

    /**
     * Upload file for authenticated master|editor user
     *
     * @param UploadRequest $request
     * @return array|Model|\Illuminate\Support\Facades\File
     * @throws Exception
     */
    public function upload(UploadRequest $request)
    {
        if (is_demo_account('howdy@hi5ve.digital')) {
            return $this->demo->upload($request);
        }

        return $this->filemanager->upload($request);
    }

    /**
     * Move item for authenticated master|editor user
     *
     * @param MoveItemRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function move(MoveItemRequest $request)
    {
        abort_if(is_demo_account('howdy@hi5ve.digital'), 204, 'Done.');

        $this->filemanager->move($request, $request->to_id);

        return response('Done!', 204);
    }

    /**
     * User download folder via zip
     *
     * @param $id
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function zip_folder($id)
    {
        $folder = Folder::whereUserId(Auth::id())
            ->where('id', $id);

        if (! $folder->exists()) {
            abort(404, "Requested folder doesn't exists.");
        }

        $zip = $this->filemanager->zip_folder($id);

        return response([
            'url'  => route('zip', $zip->id),
            'name' => $zip->basename,
        ], 201);
    }

    /**
     * User download multiple files via zip
     *
     * @param Request $request
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function zip_multiple_files(Request $request)
    {
        $files = File::whereUserId(Auth::id())
            ->whereIn('id', $request->input('items'))
            ->get();

        $zip = $this->filemanager->zip_files($files);

        return response([
            'url'  => route('zip', $zip->id),
            'name' => $zip->basename,
        ], 201);
    }
}
