<?php
namespace Domain\Files\Resources;

use ByteUnits\Metric;
use Domain\Sharing\Resources\ShareResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $fileSize = Metric::bytes($this->filesize)->format();

        return [
            'data' => [
                'id'            => $this->id,
                'type'          => $this->type,
                'attributes'    => [
                    'filesize'      => $fileSize,
                    'name'          => $this->name,
                    'basename'      => $this->basename,
                    'mimetype'      => $this->mimetype,
                    'file_url'      => $this->file_url,
                    'thumbnail'     => $this->thumbnail,
                    'metadata'      => $this->metadata,
                    'parent_id'     => $this->parent_id,
                    'created_at'    => set_time_by_user_timezone($this->owner, $this->created_at),
                    'updated_at'    => set_time_by_user_timezone($this->owner, $this->updated_at),
                    'deleted_at'    => $this->deleted_at
                        ? set_time_by_user_timezone($this->owner, $this->deleted_at)
                        : null,
                ],
                'relationships' => [
                    $this->mergeWhen($this->shared, fn () => [
                        'shared' => new ShareResource($this->shared),
                    ]),
                    $this->mergeWhen($this->parent, fn () => [
                        'parent' => [
                            'data' => [
                                'type'       => 'folder',
                                'id'         => $this->parent->id,
                                'attributes' => [
                                    'name' => $this->parent->name,
                                ],
                            ],
                        ],
                    ]),
                    $this->mergeWhen($this->owner, fn () => [
                        'owner' => [
                            'data' => [
                                'type'       => 'owner',
                                'id'         => $this->user_id,
                                'attributes' => [
                                    'name'   => $this->owner->settings->name,
                                    'avatar' => $this->owner->settings->avatar,
                                    'color'  => $this->owner->settings->color,
                                ],
                            ],
                        ],
                    ]),
                ],
            ],
        ];
    }
}
