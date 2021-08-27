<?php

namespace Domain\Files\Resources;

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
        // TODO: optimize created_at/updated_at conversion because of performance issue
        return [
            'data' => [
                'id'            => $this->id,
                'type'          => $this->type,
                'attributes'    => [
                    'name'       => $this->name,
                    'basename'   => $this->basename,
                    'mimetype'   => $this->mimetype,
                    'filesize'   => $this->filesize,
                    'file_url'   => $this->file_url,
                    'thumbnail'  => $this->thumbnail,
                    'metadata'   => $this->metadata,
                    'updated_at' => format_date(
                        set_time_by_user_timezone($this->updated_at), __t('time')
                    ),
                    'created_at' => format_date(
                        set_time_by_user_timezone($this->created_at), __t('time')
                    ),
                ],
                'relationships' => [
                    $this->mergeWhen($this->shared, fn() => [
                        'shared' => new ShareResource($this->shared),
                    ]),
                ]
            ],
        ];
    }
}
