<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Crew extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Crew $this */
        return [
            'id' => $this->id,
            'logo' => $this->whenLoaded('logo', function () {
                return $this->logo->getUrl();
            }),
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'owner_name'     => $this->whenLoaded('owner', function () {
                return $this->owner->username;
            }),
        ];
    }
}
