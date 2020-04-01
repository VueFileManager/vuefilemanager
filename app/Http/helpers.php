<?php

use ByteUnits\Metric;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;


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
    $image_path = $path . '/' . Str::random(8) . '-' . $image->getClientOriginalName();

    // Create intervention image
    $img = Image::make($image->getRealPath());

    // Generate thumbnail
    $img->fit('150', '150')->save(storage_path() . "/app/" . $image_path, 90);

    // Return path to image
    return $image_path;
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
function format_gigabytes($megabytes)
{
    return Metric::megabytes($megabytes)->format();
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
    $total = intval(Metric::megabytes($capacity)->numberOfBytes());

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
function user_storage_percentage() {

    $user = \Illuminate\Support\Facades\Auth::user();

    return get_storage_fill_percentage($user->used_capacity, config('vuefilemanager.user_storage_capacity'));
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
function filter_folders_ids($folders)
{
    $folder_unique_ids = recursiveFind($folders->toArray(), 'unique_id');

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