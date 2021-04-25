<?php
namespace App\Http\Resources;

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
        return [
            'data' => [
                'id' => $this->id,
                'type' => 'file',
                'attributes' => [
                    'name' => $this->name,
                    'basename' => $this->basename,
                    'mimetype' => $this->mimetype,
                    'filesize' => $this->filesize,
                    'type' => $this->type,
                    'file_url' => $this->file_url,
                    'thumbnail' => $this->thumbnail,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->created_at,
                ],
            ],
        ];
    }
}
