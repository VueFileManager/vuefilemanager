<?php
namespace Domain\UploadRequest\Resources;

use Domain\Folders\Resources\FolderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'data' => [
                'id'            => $this->id,
                'type'          => 'upload-request',
                'attributes'    => [
                    'folder_id'  => $this->folder_id,
                    'status'     => $this->status,
                    'email'      => $this->email,
                    'notes'      => $this->notes,
                ],
                'relationships' => [
                    $this->mergeWhen($this->folder, fn () => [
                        'folder' => new FolderResource($this->folder),
                    ]),
                    'user' => [
                        'data' => [
                            'id'         => $this->user->id,
                            'type'       => 'user',
                            'attributes' => [
                                'name'    => $this->user->settings->first_name,
                                'color'   => $this->user->settings->color,
                                'avatar'  => $this->user->settings->avatar,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
