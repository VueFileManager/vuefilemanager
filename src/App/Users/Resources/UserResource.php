<?php
namespace App\Users\Resources;

use Domain\Folders\Resources\FolderCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'id'            => $this->id,
                'type'          => 'user',
                'attributes'    => [
                    'color'                     => $this->settings->color,
                    'avatar'                    => $this->settings->avatar,
                    'email'                     => is_demo() ? obfuscate_email($this->email) : $this->email,
                    'role'                      => $this->role,
                    'two_factor_authentication' => (bool) $this->two_factor_secret,
                    'two_factor_confirmed_at'   => $this->two_factor_confirmed_at,
                    'socialite_account'         => ! (bool) $this->password,
                    'storage'                   => $this->storage,
                    'created_at'                => format_date($this->created_at, 'd. M. Y'),
                    'updated_at'                => format_date($this->updated_at, 'd. M. Y'),
                ],
                'relationships' => [
                    'settings'            => new SettingsResource($this->settings),
                    'favourites'          => new FolderCollection($this->favouriteFolders),
                ],
                'meta'          => [
                    'restrictions' => [
                        'canUpload'            => $this->canUpload(),
                        'canDownload'          => $this->canDownload(),
                        'canCreateFolder'      => $this->canCreateFolder(),
                    ],
                ],
            ],
        ];
    }
}
