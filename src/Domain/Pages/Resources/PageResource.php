<?php
namespace Domain\Pages\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
                'id'         => $this->slug,
                'type'       => 'pages',
                'attributes' => [
                    'visibility'        => $this->visibility,
                    'title'             => $this->title,
                    'slug'              => $this->slug,
                    'content'           => $this->content,
                    'content_formatted' => add_paragraphs($this->content),
                ],
            ],
        ];
    }
}
