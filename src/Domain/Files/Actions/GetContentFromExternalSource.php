<?php
namespace Domain\Files\Actions;

use App\Users\Models\User;
use Domain\Files\Models\File;
use Error;
use ErrorException;
use Illuminate\Support\Str;
use Domain\Sharing\Models\Share;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;
use Spatie\QueueableAction\QueueableAction;

class GetContentFromExternalSource
{
    use QueueableAction;

    public function __construct(
        public ProcessFileAction $processFile,
        public StoreFileExifMetadataAction $storeExifMetadata,
        public MoveFileToFTPStorageAction $moveFileToFTPStorage,
        public ProcessImageThumbnailAction $createImageThumbnail,
        public MoveFileToExternalStorageAction $moveFileToExternalStorage,
    ) {}

    public function __invoke(
        array $payload,
        User $user,
    ) {
        foreach ($payload['urls'] as $url) {
            try {
                // Get local disk instance
                $localDisk = Storage::disk('local');

                // Get file from external source
                $response = Http::get($url);

                // Get extension from response
                $extension = extractExtensionFromUrl($url, $response);

                // Get blacklisted mimetypes
                $this->checkDisabledMimetypes($extension);

                // Get file basename
                $basename = Str::uuid() . ".$extension";

                // Get file name
                $name = array_key_exists('filename', pathinfo($url))
                    ? explode('?', pathinfo($url)['filename'])[0]
                    : Str::uuid();

                // Get file path
                $path = "files/$user->id/$basename";

                // Store file to main storage disk
                $localDisk->put($path, $response->getBody());

                // Create multiple image thumbnails
                ($this->createImageThumbnail)($basename, $user->id);

                // Create new file
                $file = File::create([
                    'mimetype'   => $extension,
                    'type'       => getFileType($localDisk->mimeType($path)),
                    'parent_id'  => $payload['parent_id'] ?? null,
                    'name'       => $name ?? $basename,
                    'basename'   => $basename,
                    'filesize'   => $localDisk->size($path),
                    'user_id'    => $user->id,
                    'creator_id' => auth()->id(),
                ]);

                // Store file exif information
                ($this->storeExifMetadata)($file);

                // Move file to external storage
                match (config('filesystems.default')) {
                    's3' => ($this->moveFileToExternalStorage)($basename, $user->id),
                    'ftp', 'azure' => ($this->moveFileToFTPStorage)($basename, $user->id),
                    default => null
                };
            } catch ( ErrorException | Error $e) {
                Log::error("Remote upload failed as {$e->getMessage()}");
                Log::error($e->getTraceAsString());
            }
        }
    }

    /**
     * @param string|null $extension
     */
    protected function checkDisabledMimetypes(?string $extension): void
    {
        $mimetypeBlacklist = explode(',', get_settings('mimetypes_blacklist')) ?? null;

        // If is extension in mimetype blacklist, Abort!
        if ($extension && array_intersect([str_replace('.', '', ".$extension")], $mimetypeBlacklist)) {
            abort(422);
        }
    }
}
