<?php
namespace Domain\Files\Resources;

use ByteUnits\Metric;
use Carbon\Carbon;
use Domain\Sharing\Resources\ShareResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * TODO: optimize created_at/updated_at conversion because of performance issue
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
                    'filesize'   => $fileSize,
                    'name'       => $this->name,
                    'basename'   => $this->basename,
                    'mimetype'   => $this->mimetype,
                    'file_url'   => $this->file_url,
                    'thumbnail'  => $this->thumbnail,
                    'metadata'   => $this->metadata,
                    'folder_id'  => $this->folder_id,
                    'updated_at' => $this->updated_at,
                    'created_at'    => Carbon::parse($this->created_at)->diffForHumans(),
                    'deleted_at' => $this->deleted_at,
                    /*'updated_at' => format_date(
                        set_time_by_user_timezone($this->updated_at), __t('time')
                    ),
                    'created_at' => format_date(
                        set_time_by_user_timezone($this->created_at), __t('time')
                    ),*/
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
                ],
            ],
        ];
    }
}
