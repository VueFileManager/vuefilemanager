<?php
namespace App\Users\Resources;

use Domain\Folders\Resources\FolderCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionResource;

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
                    'avatar'                    => $this->settings->avatar,
                    'email'                     => is_demo() ? obfuscate_email($this->email) : $this->email,
                    'role'                      => $this->role,
                    'two_factor_authentication' => $this->two_factor_secret ? true : false,
                    'folders'                   => $this->folder_tree,
                    'storage'                   => $this->storage,
                    'created_at'                => format_date($this->created_at, '%d. %b. %Y'),
                    'updated_at'                => format_date($this->updated_at, '%d. %B. %Y'),
                ],
                'relationships' => [
                    'settings'    => new SettingsResource($this->settings),
                    'favourites'  => new FolderCollection($this->favouriteFolders),
                    'limitations' => [
                        'id'   => $this->id,
                        'type' => 'limitations',
                        'data' => [
                            'attributes' => $this->limitations,
                        ],
                    ],
                    $this->mergeWhen($this->hasSubscription(), fn () => [
                        'subscription' => new SubscriptionResource($this->subscription),
                    ]),
                ],
                'meta' => [
                    'limitations' => $this->limitations->summary(),
                ],
            ],
        ];
    }
}
