<?php

use App\FileManagerFile;
use App\FileManagerFolder;
use App\Share;
use ByteUnits\Metric;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Get app version from config
 *
 * @return \Illuminate\Config\Repository|mixed
 */
function get_storage() {
    return env('FILESYSTEM_DRIVER');
}

/**
 * Check if is running AWS s3 as storage
 *
 * @return bool
 */
function is_storage_driver($driver) {

    if (is_array($driver)) {
        return in_array(env('FILESYSTEM_DRIVER'), $driver);
    }

    return env('FILESYSTEM_DRIVER') === $driver;
}

/**
 * Get app version from config
 *
 * @return \Illuminate\Config\Repository|mixed
 */
function get_version() {
    return config('vuefilemanager.version');
}

/**
 * Check if is demo
 *
 * @return mixed
 */
function is_demo($user_id) {

    return env('APP_DEMO', false) && $user_id === 1;
}

/**
 * Get folder or file item
 *
 * @param $type
 * @param $unique_id
 * @param $user_id
 * @return \Illuminate\Database\Eloquent\Builder|Model
 */
function get_item($type, $unique_id, $user_id) {

    if ($type === 'folder') {

        // Return folder item
        return FileManagerFolder::where('unique_id', $unique_id)
            ->where('user_id', $user_id)
            ->firstOrFail();
    }

    // Return file item
    return FileManagerFile::where('unique_id', $unique_id)
        ->where('user_id', $user_id)
        ->firstOrFail();
}

/**
 * Get shared token
 *
 * @param $token
 * @return \Illuminate\Database\Eloquent\Builder|Model
 */
function get_shared($token) {

    return Share::where(DB::raw('BINARY `token`'), $token)
        ->firstOrFail();
}

/**
 * Check if shared permission is editor
 *
 * @param $shared
 * @return bool
 */
function is_editor($shared) {

    return $shared->permission === 'editor';
}

/**
 * Get unique id
 *
 * @return int
 */
function get_unique_id(): int
{
    // Get files and folders
    $folders = FileManagerFolder::withTrashed()->get();
    $files = FileManagerFile::withTrashed()->get();

    // Get last ids
    $folders_unique = $folders->isEmpty() ? 0 : $folders->last()->unique_id;
    $files_unique = $files->isEmpty() ? 0 : $files->last()->unique_id;

    // Count new unique id
    $unique_id = $folders_unique > $files_unique ? $folders_unique + 1 : $files_unique + 1;

    return $unique_id;
}

/**
 * Store user avatar to storage
 *
 * @param $image
 * @param $path
 * @return string
 */
function store_avatar($image, $path)
{
    // Get directory
    $path = check_directory($path);

    // Store avatar
    $image_path = Str::random(8) . '-' . $image->getClientOriginalName();

    // Create intervention image
    $img = Image::make($image->getRealPath());

    // Generate thumbnail
    $img->fit('150', '150')->stream();

    // Store thumbnail to disk
    Storage::put($path . '/' . $image_path, $img);

    // Return path to image
    return $path . '/' . $image_path;
}

/**
 * Check if directory exist, if no, then create it
 *
 * @param $directory
 * @return mixed
 */
function check_directory($directory)
{
    if (!Storage::exists($directory)) {
        Storage::makeDirectory($directory);
    }

    return $directory;
}

/**
 * Make input from request
 *
 * @param $request
 * @return array
 */
function make_single_input($request)
{
    // Create container
    $data = [];

    // Add data to array
    $data[$request->name] = $request->value;

    // Return input
    return $data;
}

/**
 * Format integer to gigabytes
 *
 * @param $gigabytes
 * @return string
 */
function format_gigabytes($gigabytes)
{
    return Metric::gigabytes($gigabytes)->format();
}

/**
 * Get storage usage in percent
 *
 * @param $used
 * @param $capacity
 * @return string
 */
function get_storage_fill_percentage($used, $capacity)
{
    // Format gigabytes to bytes
    $total = intval(Metric::gigabytes($capacity)->numberOfBytes());

    // Count progress
    $progress = ($used * 100) / $total;

    // Return in 2 decimal
    return number_format((float)$progress, 2, '.', '');
}

/**
 * Get user capacity fill percentage
 *
 * @return string
 */
function user_storage_percentage()
{
    $user = Auth::user();

    return get_storage_fill_percentage($user->used_capacity, $user->settings->storage_capacity);
}

/**
 * Find all key values in recursive array
 *
 * @param array $array
 * @param $needle
 * @return array
 */
function recursiveFind(array $array, $needle)
{
    $iterator = new RecursiveArrayIterator($array);
    $recursive = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
    $aHitList = array();
    foreach ($recursive as $key => $value) {
        if ($key === $needle) {
            array_push($aHitList, $value);
        }
    }
    return $aHitList;
}

/**
 * Get values which appears only once in array
 * @param $arr
 * @return array
 */
function appeared_once($arr)
{
    $array_count_values = array_count_values($arr);

    $single_time_comming_values_array = [];

    foreach ($array_count_values as $key => $val) {

        if ($val == 1) {
            $single_time_comming_values_array[] = $key;
        }
    }

    return $single_time_comming_values_array;
}

/**
 * @param $folders
 * @return array
 */
function filter_folders_ids($folders, $by_column = 'unique_id')
{
    $folder_unique_ids = recursiveFind($folders->toArray(), $by_column);

    return appeared_once($folder_unique_ids);
}

/**
 * Format localized date
 *
 * @param $date
 * @param string $format
 * @return string
 */
function format_date($date, $format = '%d. %B. %Y, %H:%M')
{
    $start = Carbon::parse($date);

    return $start->formatLocalized($format);
}

/**
 * Get file type from mimetype
 *
 * @param $file
 * @return string
 */
function get_file_type($file)
{
    // Get mimetype from file
    $mimetype = explode('/', $file->getMimeType());

    switch ($mimetype[0]) {
        case 'image':
            return 'image';
            break;
        case 'video':
            return 'video';
            break;
        case 'audio':
            return 'audio';
            break;
        default:
            return 'file';
    }
}