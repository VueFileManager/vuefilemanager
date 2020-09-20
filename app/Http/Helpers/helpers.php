<?php

use App\FileManagerFile;
use App\FileManagerFolder;
use App\Setting;
use App\Share;
use ByteUnits\Metric;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Obfuscate email
 *
 * @param $email
 * @return string
 */
function obfuscate_email($email)
{
    $em = explode("@", $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $len = floor(strlen($name) / 2);

    return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

/**
 * Get single value from settings table
 *
 * @param $setting
 * @return |null
 */
function get_setting($setting)
{
    $row = Setting::where('name', $setting)->first();

    return $row ? $row->value : null;
}

/**
 * Create paragraph from text
 *
 * @param $str
 * @return mixed|null|string|string[]
 */
function add_paragraphs($str)
{
    // Trim whitespace
    if (($str = trim($str)) === '') return '';

    // Standardize newlines
    $str = str_replace(array("\r\n", "\r"), "\n", $str);

    // Trim whitespace on each line
    $str = preg_replace('~^[ \t]+~m', '', $str);
    $str = preg_replace('~[ \t]+$~m', '', $str);

    // The following regexes only need to be executed if the string contains html
    if ($html_found = (strpos($str, '<') !== FALSE)) {
        // Elements that should not be surrounded by p tags
        $no_p = '(?:p|div|article|header|aside|hgroup|canvas|output|progress|section|figcaption|audio|video|nav|figure|footer|video|details|main|menu|summary|h[1-6r]|ul|ol|li|blockquote|d[dlt]|pre|t[dhr]|t(?:able|body|foot|head)|c(?:aption|olgroup)|form|s(?:elect|tyle)|a(?:ddress|rea)|ma(?:p|th))';

        // Put at least two linebreaks before and after $no_p elements
        $str = preg_replace('~^<' . $no_p . '[^>]*+>~im', "\n$0", $str);
        $str = preg_replace('~</' . $no_p . '\s*+>$~im', "$0\n", $str);
    }

    // Do the <p> magic!
    $str = '<p>' . trim($str) . '</p>';
    $str = preg_replace('~\n{2,}~', "</p>\n\n<p>", $str);

    // The following regexes only need to be executed if the string contains html
    if ($html_found !== FALSE) {
        // Remove p tags around $no_p elements
        $str = preg_replace('~<p>(?=</?' . $no_p . '[^>]*+>)~i', '', $str);
        $str = preg_replace('~(</?' . $no_p . '[^>]*+>)</p>~i', '$1', $str);
    }

    // Convert single linebreaks to <br />
    $str = preg_replace('~(?<!\n)\n(?!\n)~', "<br>\n", $str);

    return $str;
}

/**
 * Set environment value
 *
 * @param $key
 * @param $value
 * @return bool
 */
function setEnvironmentValue(array $values)
{
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
        foreach ($values as $envKey => $envValue) {

            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            // If key does not exist, add it
            $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        }
    }

    $str = substr($str, 0, -1);
    if (!file_put_contents($envFile, $str)) return false;
    return true;

}

/**
 * Get invoice number
 *
 * @return string
 */
function get_invoice_number()
{
    $invoices = \App\Invoice::all();

    if ($invoices->isEmpty()) {
        return Carbon::now()->year . '001';
    } else {
        return (int)$invoices->last()->order + 1;
    }
}

/**
 * Forget many cache keys at once
 * @param $cache
 */
function cache_forget_many($cache)
{
    foreach ($cache as $item) {
        \Illuminate\Support\Facades\Cache::forget($item);
    }
}

/**
 * Get app version from config
 *
 * @return \Illuminate\Config\Repository|mixed
 */
function get_storage()
{
    return env('FILESYSTEM_DRIVER');
}

/**
 * Check if is running AWS s3 as storage
 *
 * @return bool
 */
function is_storage_driver($driver)
{
    if (is_array($driver)) {
        return in_array(config('filesystems.default'), $driver);
    }

    return config('filesystems.default') === $driver;
}

/**
 * Get app version from config
 *
 * @return \Illuminate\Config\Repository|mixed
 */
function get_version()
{
    return config('vuefilemanager.version');
}

/**
 * Check if is demo
 *
 * @return mixed
 */
function is_demo($user_id)
{
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
function get_item($type, $unique_id, $user_id)
{

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
function get_shared($token)
{

    return Share::where(DB::raw('BINARY `token`'), $token)
        ->firstOrFail();
}

/**
 * Check if shared permission is editor
 *
 * @param $shared
 * @return bool
 */
function is_editor($shared)
{

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
 * Store system image
 *
 * @param $image
 * @param $path
 * @return string
 */
function store_system_image($image, $path)
{
    // Get directory
    $path = check_directory($path);

    // Store avatar
    $image_path = Str::random(8) . '-' . str_replace(' ', '', $image->getClientOriginalName());

    // Store image to disk
    Storage::putFileAs($path, $image, $image_path);

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
    if ($gigabytes >= 1000) {
        return Metric::gigabytes($gigabytes)->format('Tb/');
    } else {
        return Metric::gigabytes($gigabytes)->format('GB/');
    }
}

/**
 * Convert megabytes to bytes
 *
 * @param $megabytes
 * @return int|string
 */
function format_bytes($megabytes)
{
    return Metric::megabytes($megabytes)->numberOfBytes();
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
 * Get user capacity fill by percentage
 *
 * @return string
 */
function user_storage_percentage($id, $additionals = null)
{
    $user = \App\User::findOrFail($id);

    $used = $user->used_capacity;

    if ($additionals) {
        $used = $user->used_capacity + $additionals;
    }

    return get_storage_fill_percentage($used, $user->settings->storage_capacity);
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
function get_file_type($file_mimetype)
{
    // Get mimetype from file
    $mimetype = explode('/', $file_mimetype);

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


/**
 * Get file type from mimetype
 *
 * @param $mimetype
 * @return mixed
 */
function get_file_type_from_mimetype($mimetype)
{
    return explode('/', $mimetype)[1];
}

/**
 * Format pretty name file
 *
 * @param $basename
 * @param $name
 * @param $mimetype
 * @return string
 */
function get_pretty_name($basename, $name, $mimetype)
{
    $file_extension = substr(strrchr($basename, '.'), 1);

    if (strpos($name, $file_extension) !== false) {
        return $name;
    }

    if ($file_extension) {
        return $name . '.' . $file_extension;
    }

    return $name . '.' . $mimetype;
}

function get_image_meta_data($file)
{
    if(get_file_type_from_mimetype($file->getMimeType()) === 'jpeg') {
       return  exif_read_data($file);
    }
}
