<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request) {
        /** @var \App\Models\Event $this */
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'logo'       => $this->whenLoaded('logo', function () {
                return $this->logo->getUrl();
            }),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
