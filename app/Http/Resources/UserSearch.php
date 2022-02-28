<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSearch extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request) {
        /** @var \App\Models\User $this */
        return [
            'id'         => $this->id,
            'username'   => $this->username,
            'logo'       => $this->whenLoaded('logo', function () {
                return $this->logo->getUrl();
            }),
            'is_in_crew' => !empty($this->is_in_crew),
        ];
    }
}
