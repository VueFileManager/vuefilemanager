<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Share
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property int $item_id
 * @property string $type
 * @property string|null $permission
 * @property int $protected
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $link
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share whereProtected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Share whereUserId($value)
 * @mixin \Eloquent
 */
class Share extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['link'];

    /**
     * Generate share link
     *
     * @return string
     */
    public function getLinkAttribute() {

        return url('/shared', ['token' => $this->attributes['token']]);
    }
}
