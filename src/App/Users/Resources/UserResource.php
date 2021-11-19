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
        // TODO: zrefaktorovat
        return [
            'data' => [
                'id'         => $this->id,
                'type'       => 'user',
                'attributes' => [
                    'max_storage_amount'          => $this->settings->max_storage_amount,
                    'email'                       => is_demo() ? obfuscate_email($this->email) : $this->email,
                    'role'                        => $this->role,
                    'two_factor_authentication'   => $this->two_factor_secret ? true : false,
                    'folders'                     => $this->folder_tree,
                    'storage'                     => $this->storage,
                    'created_at_formatted'        => format_date($this->created_at, '%d. %B. %Y'),
                    'created_at'                  => $this->created_at,
                    'updated_at'                  => $this->updated_at,
                ],
                'relationships' => [
                    'settings' => [
                        'data' => [
                            'id'         => $this->id,
                            'type'       => 'settings',
                            'attributes' => [
                                'avatar'       => $this->settings->avatar,
                                'name'         => $this->settings->name,
                                'address'      => $this->settings->address,
                                'state'        => $this->settings->state,
                                'city'         => $this->settings->city,
                                'postal_code'  => $this->settings->postal_code,
                                'country'      => $this->settings->country,
                                'phone_number' => $this->settings->phone_number,
                                'timezone'     => $this->settings->timezone,
                            ],
                        ],
                    ],
                    'favourites' => new FolderCollection($this->favouriteFolders),
                ],
            ],
        ];
    }
}
