<?php
namespace Domain\Notifications\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
                'id'         => $this->id,
                'type'       => $this->type,
                'attributes' => [
                    'category'    => $this->data['category'],
                    'title'       => $this->data['title'],
                    'description' => $this->data['description'],
                    'action'      => $this->data['action'] ?? null,
                    'created_at'  => format_date($this->created_at, 'd. M. Y h:i'),
                    'read_at'     => $this->read_at ? format_date($this->read_at, 'd. M. Y h:i') : null,
                ],
            ],
        ];
    }
}
