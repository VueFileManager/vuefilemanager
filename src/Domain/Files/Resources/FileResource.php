<?php
namespace Domain\Files\Resources;

use Carbon\Carbon;
use ByteUnits\Metric;
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
                    'parent_id'  => $this->parent_id,
                    'updated_at' => $this->updated_at,
                    'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
                    'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->diffForHumans() : null,
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
                    $this->mergeWhen($this->exif, fn() => [
                        'metadata' => [
                            'date_time_original' => format_date($this->exif->date_time_original) ,
                            'artist'             => $this->exif->artist ,
                            'height'             => $this->exif->height,
                            'width'              => $this->exif->width,
                            'x_resolution'       => substr($this->exif->x_resolution, 0, strrpos( $this->exif->x_resolution, '/')),
                            'y_resolution'       => substr($this->exif->y_resolution, 0, strrpos( $this->exif->y_resolution, '/')) ,
                            'color_space'        => $this->exif->color_space ,
                            'camera'             => $this->exif->camera,
                            'model'              => $this->exif->model ,
                            'aperture_value'     => intval($this->exif->aperture_value) / 100 ,
                            'exposure_time'      => $this->exif->exposure_time ,
                            'focal_length'       => $this->exif->focal_length ,
                            'iso'                => $this->exif->iso ,
                            'aperture_f_number'  => $this->exif->aperture_f_number ,
                            'ccd_width'          => $this->exif->ccd_width ,
                            'longitude'          => format_gps_coordinates($this->exif->longitude, $this->exif->longitude_ref) ,
                            'latitude'           => format_gps_coordinates($this->exif->latitude, $this->exif->latitude_ref) ,
                        ]
                    ])
                ],
            ],
        ];
    }
}
