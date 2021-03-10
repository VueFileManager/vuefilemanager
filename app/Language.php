<?php

namespace App;

use App\LanguageString;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    protected $guarded = ['id'];

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false ;

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($language) {
            $language->id = Str::uuid();
        });

        static::deleting(function ($language) {
            $language->languageStrings()->delete();
        });

        static::created(function ($language) {

            $license = get_setting('license') === 'Extended' ? 'extended' : 'regular';

            $language_strings = collect(config('language_strings.' . $license));

    
            $strings = $language_strings->map(function ($value , $key) use($language) {
    
               return [
                    'language_id' => $language->id,
                    'key'         => $key,
                    'lang'        => $language->locale,
                    'value'       => $value
                ];
    
            })->toArray();
    
            DB::table('language_strings')->insert($strings);

        });
    }

    public function languageStrings()
    {
        return $this->hasMany('App\LanguageString', 'language_id', 'id');
    }
}
