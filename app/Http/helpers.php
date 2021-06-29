<?php

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\Share;
use ByteUnits\Metric;
use App\Models\Folder;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Obfuscate email
 *
 * @param $email
 * @return string
 */
function obfuscate_email($email)
{
    $em = explode('@', $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $len = floor(strlen($name) / 2);

    return substr($name, 0, $len) . str_repeat('*', $len) . '@' . end($em);
}

/**
 * Get single value from settings table
 *
 * @param $setting
 * @return |null
 */
function get_setting($setting)
{
    return Setting::find($setting)->value ?? null;
}

/**
 * Get all app settings and return them as json
 */
function get_settings_in_json()
{
    return json_decode(
        Setting::all()
            ->pluck('value', 'name')
            ->toJson()
    );
}

/**
 * Check if setup wizard was passed
 *
 * @return string
 */
function get_setup_status()
{
    $setup_success = get_setting('setup_wizard_success');

    return boolval($setup_success) ? 'setup-done' : 'setup-disclaimer';
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
    if (($str = trim($str)) === '') {
        return '';
    }

    // Standardize newlines
    $str = str_replace(["\r\n", "\r"], "\n", $str);

    // Trim whitespace on each line
    $str = preg_replace('~^[ \t]+~m', '', $str);
    $str = preg_replace('~[ \t]+$~m', '', $str);

    // The following regexes only need to be executed if the string contains html
    if ($html_found = (strpos($str, '<') !== false)) {
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
    if ($html_found !== false) {
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

    return !(!file_put_contents($envFile, $str));
}

if (!function_exists('get_invoice_number')) {
    /**
     * Get invoice number
     *
     * @return string
     */
    function get_invoice_number()
    {
        $invoices = Invoice::all();

        if ($invoices->isEmpty()) {
            return now()->year . '001';
        }

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
 * @return bool
 */
function is_demo()
{
    return config('vuefilemanager.is_demo');
}

/**
 * Check if is demo
 *
 * @param $email
 * @return mixed
 */
function is_demo_account($email)
{
    return config('vuefilemanager.is_demo') && $email === 'howdy@hi5ve.digital';
}

/**
 * Get folder or file item
 *
 * @param $type
 * @param $id
 * @return \Illuminate\Database\Eloquent\Builder|Model
 */
function get_item($type, $id)
{
    $model = strtolower($type) === 'folder' ? 'Folder' : 'File';

    return ("App\\Models\\$model")::find($id);
}

/**
 * Get shared token
 *
 * @param $token
 * @return \Illuminate\Database\Eloquent\Builder|Model
 */
function get_shared($token)
{
    return Share::whereToken($token)
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
 * Check if shared permission is visitor
 *
 * @param $shared
 * @return bool
 */
function is_visitor($shared)
{
    return $shared->permission === 'visitor';
}

/**
 * Store user avatar to storage
 *
 * @param $request
 * @param $name
 * @return string|null
 */
function store_avatar($request, $name)
{
    if (!$request->hasFile($name)) {
        return null;
    }

    $image = $request->file($name);

    // Store avatar
    $image_path = Str::random(16) . '-' . $image->getClientOriginalName();

    if (in_array($image->getClientMimeType(), ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
        // Create intervention image
        $img = Image::make($image->getRealPath());

        // Generate thumbnail
        $img->fit('150', '150')->stream();

        // Store thumbnail to disk
        Storage::put("avatars/$image_path", $img);
    }

    if ($image->getClientMimeType() === 'image/svg+xml') {
        Storage::putFileAs('avatars', $image, $image_path);
    }

    // Return path to image
    return "avatars/$image_path";
}

/**
 * Store system image
 *
 * @param $request
 * @param $name
 * @return string|null
 */
function store_system_image($request, $name)
{
    if (!$request->hasFile($name)) {
        return null;
    }

    $image = $request->file($name);

    // Store avatar
    $filename = Str::random(8) . '-' . str_replace(' ', '', $image->getClientOriginalName());

    // Store image to disk
    Storage::putFileAs('system', $image, $filename);

    // Return path to image
    return "system/$filename";
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
    }

    return Metric::gigabytes($gigabytes)->format('GB/');
}

/**
 * Format string to formated megabytes string
 *
 * @param $megabytes
 * @return string
 */
function format_megabytes($megabytes)
{
    if ($megabytes >= 1000) {
        return $megabytes / 1000 . 'GB';
    }

    if ($megabytes >= 1000000) {
        return $megabytes / 1000000 . 'TB';
    }

    return $megabytes . 'MB';
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
    if ($total == 0) {
        $progress = 100;
    } else {
        $progress = ($used * 100) / $total;
    }

    // Return in 2 decimal
    return number_format((float)$progress, 2, '.', '');
}

/**
 * Get user capacity fill by percentage
 *
 * @param $id
 * @param null $additionals
 * @return string
 */
function user_storage_percentage($id, $additionals = null)
{
    $user = User::findOrFail($id);

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
    $aHitList = [];

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
 * @param string $by_column
 * @return array
 */
function filter_folders_ids($folders, $by_column = 'id')
{
    $folder_ids = recursiveFind($folders->toArray(), $by_column);

    return appeared_once($folder_ids);
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
 * @param $file_mimetype
 * @return string
 */
function get_file_type($file_mimetype)
{
    // Get mimetype from file
    $mimetype = explode('/', $file_mimetype);

    if (in_array($mimetype[0], ['image', 'video', 'audio'])) {
        return $mimetype[0];
    }

    return 'file';
}

if (!function_exists('map_language_translations')) {
    /**
     * It map language translations as language key and language value
     *
     * @param $translations
     * @return mixed
     */
    function map_language_translations($translations): Collection
    {
        return $translations->map(function ($string) {
            return [$string->key => $string->value];
        })->collapse();
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

/**
 * Get exif data from jpeg image
 *
 * @param $file
 * @return array|null
 */
function get_image_meta_data($file)
{
    if (get_file_type_from_mimetype($file->getMimeType()) === 'jpeg') {
        try {
            // Try to get the exif data
            return mb_convert_encoding(Image::make($file->getRealPath())->exif(), 'UTF8', 'UTF8');
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('get_default_language_translations')) {
    /**
     * @return Collection
     */
    function get_default_language_translations(): Collection
    {
        return collect([
            config('language-translations.extended'),
            config('language-translations.regular'),
            config('custom-language-translations'),
        ])->collapse();
    }
}

/**
 * Check if app is in dev mode
 *
 * @return bool
 */
function is_dev()
{
    return env('APP_ENV') === 'local';
}

/**
 * @param $str
 * @return bool
 */
function seems_utf8($str)
{
    $length = strlen($str);

    for ($i = 0; $i < $length; $i++) {
        $c = ord($str[$i]);

        if ($c < 0x80) {
            $n = 0;
        } # 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) {
            $n = 1;
        } # 110bbbbb
        elseif (($c & 0xF0) == 0xE0) {
            $n = 2;
        } # 1110bbbb
        elseif (($c & 0xF8) == 0xF0) {
            $n = 3;
        } # 11110bbb
        elseif (($c & 0xFC) == 0xF8) {
            $n = 4;
        } # 111110bb
        elseif (($c & 0xFE) == 0xFC) {
            $n = 5;
        } # 1111110b
        else {
            return false;
        } # Does not match any model

        for ($j = 0; $j < $n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) {
                return false;
            }
        }
    }

    return true;
}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function remove_accents($string)
{
    if (!preg_match('/[\x80-\xff]/', $string)) {
        return $string;
    }

    if (seems_utf8($string)) {
        $chars = [
            // Decompositions for Latin-1 Supplement
            chr(195) . chr(128)            => 'A', chr(195) . chr(129) => 'A',
            chr(195) . chr(130)            => 'A', chr(195) . chr(131) => 'A',
            chr(195) . chr(132)            => 'A', chr(195) . chr(133) => 'A',
            chr(195) . chr(135)            => 'C', chr(195) . chr(136) => 'E',
            chr(195) . chr(137)            => 'E', chr(195) . chr(138) => 'E',
            chr(195) . chr(139)            => 'E', chr(195) . chr(140) => 'I',
            chr(195) . chr(141)            => 'I', chr(195) . chr(142) => 'I',
            chr(195) . chr(143)            => 'I', chr(195) . chr(145) => 'N',
            chr(195) . chr(146)            => 'O', chr(195) . chr(147) => 'O',
            chr(195) . chr(148)            => 'O', chr(195) . chr(149) => 'O',
            chr(195) . chr(150)            => 'O', chr(195) . chr(153) => 'U',
            chr(195) . chr(154)            => 'U', chr(195) . chr(155) => 'U',
            chr(195) . chr(156)            => 'U', chr(195) . chr(157) => 'Y',
            chr(195) . chr(159)            => 's', chr(195) . chr(160) => 'a',
            chr(195) . chr(161)            => 'a', chr(195) . chr(162) => 'a',
            chr(195) . chr(163)            => 'a', chr(195) . chr(164) => 'a',
            chr(195) . chr(165)            => 'a', chr(195) . chr(167) => 'c',
            chr(195) . chr(168)            => 'e', chr(195) . chr(169) => 'e',
            chr(195) . chr(170)            => 'e', chr(195) . chr(171) => 'e',
            chr(195) . chr(172)            => 'i', chr(195) . chr(173) => 'i',
            chr(195) . chr(174)            => 'i', chr(195) . chr(175) => 'i',
            chr(195) . chr(177)            => 'n', chr(195) . chr(178) => 'o',
            chr(195) . chr(179)            => 'o', chr(195) . chr(180) => 'o',
            chr(195) . chr(181)            => 'o', chr(195) . chr(182) => 'o',
            chr(195) . chr(182)            => 'o', chr(195) . chr(185) => 'u',
            chr(195) . chr(186)            => 'u', chr(195) . chr(187) => 'u',
            chr(195) . chr(188)            => 'u', chr(195) . chr(189) => 'y',
            chr(195) . chr(191)            => 'y',
            // Decompositions for Latin Extended-A
            chr(196) . chr(128)            => 'A', chr(196) . chr(129) => 'a',
            chr(196) . chr(130)            => 'A', chr(196) . chr(131) => 'a',
            chr(196) . chr(132)            => 'A', chr(196) . chr(133) => 'a',
            chr(196) . chr(134)            => 'C', chr(196) . chr(135) => 'c',
            chr(196) . chr(136)            => 'C', chr(196) . chr(137) => 'c',
            chr(196) . chr(138)            => 'C', chr(196) . chr(139) => 'c',
            chr(196) . chr(140)            => 'C', chr(196) . chr(141) => 'c',
            chr(196) . chr(142)            => 'D', chr(196) . chr(143) => 'd',
            chr(196) . chr(144)            => 'D', chr(196) . chr(145) => 'd',
            chr(196) . chr(146)            => 'E', chr(196) . chr(147) => 'e',
            chr(196) . chr(148)            => 'E', chr(196) . chr(149) => 'e',
            chr(196) . chr(150)            => 'E', chr(196) . chr(151) => 'e',
            chr(196) . chr(152)            => 'E', chr(196) . chr(153) => 'e',
            chr(196) . chr(154)            => 'E', chr(196) . chr(155) => 'e',
            chr(196) . chr(156)            => 'G', chr(196) . chr(157) => 'g',
            chr(196) . chr(158)            => 'G', chr(196) . chr(159) => 'g',
            chr(196) . chr(160)            => 'G', chr(196) . chr(161) => 'g',
            chr(196) . chr(162)            => 'G', chr(196) . chr(163) => 'g',
            chr(196) . chr(164)            => 'H', chr(196) . chr(165) => 'h',
            chr(196) . chr(166)            => 'H', chr(196) . chr(167) => 'h',
            chr(196) . chr(168)            => 'I', chr(196) . chr(169) => 'i',
            chr(196) . chr(170)            => 'I', chr(196) . chr(171) => 'i',
            chr(196) . chr(172)            => 'I', chr(196) . chr(173) => 'i',
            chr(196) . chr(174)            => 'I', chr(196) . chr(175) => 'i',
            chr(196) . chr(176)            => 'I', chr(196) . chr(177) => 'i',
            chr(196) . chr(178)            => 'IJ', chr(196) . chr(179) => 'ij',
            chr(196) . chr(180)            => 'J', chr(196) . chr(181) => 'j',
            chr(196) . chr(182)            => 'K', chr(196) . chr(183) => 'k',
            chr(196) . chr(184)            => 'k', chr(196) . chr(185) => 'L',
            chr(196) . chr(186)            => 'l', chr(196) . chr(187) => 'L',
            chr(196) . chr(188)            => 'l', chr(196) . chr(189) => 'L',
            chr(196) . chr(190)            => 'l', chr(196) . chr(191) => 'L',
            chr(197) . chr(128)            => 'l', chr(197) . chr(129) => 'L',
            chr(197) . chr(130)            => 'l', chr(197) . chr(131) => 'N',
            chr(197) . chr(132)            => 'n', chr(197) . chr(133) => 'N',
            chr(197) . chr(134)            => 'n', chr(197) . chr(135) => 'N',
            chr(197) . chr(136)            => 'n', chr(197) . chr(137) => 'N',
            chr(197) . chr(138)            => 'n', chr(197) . chr(139) => 'N',
            chr(197) . chr(140)            => 'O', chr(197) . chr(141) => 'o',
            chr(197) . chr(142)            => 'O', chr(197) . chr(143) => 'o',
            chr(197) . chr(144)            => 'O', chr(197) . chr(145) => 'o',
            chr(197) . chr(146)            => 'OE', chr(197) . chr(147) => 'oe',
            chr(197) . chr(148)            => 'R', chr(197) . chr(149) => 'r',
            chr(197) . chr(150)            => 'R', chr(197) . chr(151) => 'r',
            chr(197) . chr(152)            => 'R', chr(197) . chr(153) => 'r',
            chr(197) . chr(154)            => 'S', chr(197) . chr(155) => 's',
            chr(197) . chr(156)            => 'S', chr(197) . chr(157) => 's',
            chr(197) . chr(158)            => 'S', chr(197) . chr(159) => 's',
            chr(197) . chr(160)            => 'S', chr(197) . chr(161) => 's',
            chr(197) . chr(162)            => 'T', chr(197) . chr(163) => 't',
            chr(197) . chr(164)            => 'T', chr(197) . chr(165) => 't',
            chr(197) . chr(166)            => 'T', chr(197) . chr(167) => 't',
            chr(197) . chr(168)            => 'U', chr(197) . chr(169) => 'u',
            chr(197) . chr(170)            => 'U', chr(197) . chr(171) => 'u',
            chr(197) . chr(172)            => 'U', chr(197) . chr(173) => 'u',
            chr(197) . chr(174)            => 'U', chr(197) . chr(175) => 'u',
            chr(197) . chr(176)            => 'U', chr(197) . chr(177) => 'u',
            chr(197) . chr(178)            => 'U', chr(197) . chr(179) => 'u',
            chr(197) . chr(180)            => 'W', chr(197) . chr(181) => 'w',
            chr(197) . chr(182)            => 'Y', chr(197) . chr(183) => 'y',
            chr(197) . chr(184)            => 'Y', chr(197) . chr(185) => 'Z',
            chr(197) . chr(186)            => 'z', chr(197) . chr(187) => 'Z',
            chr(197) . chr(188)            => 'z', chr(197) . chr(189) => 'Z',
            chr(197) . chr(190)            => 'z', chr(197) . chr(191) => 's',
            // Euro Sign
            chr(226) . chr(130) . chr(172) => 'E',
            // GBP (Pound) Sign
            chr(194) . chr(163)            => '',];

        $string = strtr($string, $chars);
    } else {
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = chr(128) . chr(131) . chr(138) . chr(142) . chr(154) . chr(158)
            . chr(159) . chr(162) . chr(165) . chr(181) . chr(192) . chr(193) . chr(194)
            . chr(195) . chr(196) . chr(197) . chr(199) . chr(200) . chr(201) . chr(202)
            . chr(203) . chr(204) . chr(205) . chr(206) . chr(207) . chr(209) . chr(210)
            . chr(211) . chr(212) . chr(213) . chr(214) . chr(216) . chr(217) . chr(218)
            . chr(219) . chr(220) . chr(221) . chr(224) . chr(225) . chr(226) . chr(227)
            . chr(228) . chr(229) . chr(231) . chr(232) . chr(233) . chr(234) . chr(235)
            . chr(236) . chr(237) . chr(238) . chr(239) . chr(241) . chr(242) . chr(243)
            . chr(244) . chr(245) . chr(246) . chr(248) . chr(249) . chr(250) . chr(251)
            . chr(252) . chr(253) . chr(255);

        $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in'] = [chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254)];
        $double_chars['out'] = ['OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th'];
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

/**
 * Get all files from folder and get their folder location in VueFileManager directories
 *
 * @param $folders
 * @param null $files
 * @param array $path
 * @return array
 */
function get_files_for_zip($folders, $files, $path = [])
{
    // Return file list
    if (!isset($folders->folders)) {
        return $files->unique()->values()->all();
    }

    // Push file path
    array_push($path, $folders->name);

    // Push file to collection
    $folders->files->each(function ($file) use ($files, $path) {
        $files->push([
            'name'        => $file->name,
            'basename'    => $file->basename,
            'mimetype'    => $file->mimetype,
            'folder_path' => implode('/', $path),
        ]);
    });

    // Get all children folders and folders within
    if ($folders->folders->isNotEmpty()) {
        $folders->folders->map(function ($folder) use ($files, $path) {
            return get_files_for_zip($folder, $files, $path);
        });
    }

    return get_files_for_zip($folders->folders->first(), $files, $path);
}

/**
 * Set time by user timezone GMT
 *
 * @param $time
 * @return Carbon
 */
function set_time_by_user_timezone($time)
{
    $user = Auth::user();

    if ($user) {
        $time_zone = intval($user->settings->timezone * 60 ?? null);

        return Carbon::parse($time)->addMinutes($time_zone ?? 0);
    }

    return Carbon::parse($time);
}

if (!function_exists('__t')) {
    /**
     * Translate the given message.
     * @param $key
     * @param null $values
     * @return string
     * @throws Exception
     */
    function __t($key, $values = null): string
    {
        // Get current locale
        $locale = cache()->rememberForever('language', function () {
            try {
                return get_setting('language') ?? 'en';
            } catch (QueryException $e) {
                return 'en';
            }
        });

        // Get language strings
        $strings = cache()->rememberForever("language-translations-$locale", function () use ($locale) {
                try {
                    return Language::whereLocale($locale)->firstOrFail()->languageTranslations;
                } catch (QueryException | ModelNotFoundException $e) {
                    return null;
                }
            }) ?? get_default_language_translations();

        // Find the string by key
        $string = $strings->firstWhere('key', $key)->value ?? $strings->get($key);

        if ($values) {
            return replace_occurrence($string, collect($values));
        }

        return $string;
    }
}

if (!function_exists('replace_occurrence')) {
    /**
     * Replace string occurrence in __t() by their values
     *
     * @param $string
     * @param $values
     * @return string|string[]
     */
    function replace_occurrence($string, $values)
    {
        $occurrences = $values->map(function ($message, $key) {
            return [
                'key'     => ":$key",
                'message' => $message,
            ];
        });

        return str_ireplace(
            $occurrences->pluck('key')->toArray(),
            $occurrences->pluck('message')->toArray(),
            $string
        );
    }
}
