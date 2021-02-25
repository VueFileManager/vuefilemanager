<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Traffic
 *
 * @property int $id
 * @property int $user_id
 * @property int $upload
 * @property int $download
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereUserId($value)
 * @mixin \Eloquent
 */
class Traffic extends Model
{
    protected $fillable = ['user_id', 'upload', 'download'];
}
