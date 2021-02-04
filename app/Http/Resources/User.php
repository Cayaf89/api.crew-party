<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'firstname'  => $this->firstname,
            'lastname'   => $this->lastname,
            'username'   => $this->username,
            'email'      => $this->email,
            'logo' => $this->whenLoaded('logo', function () {
                return $this->logo->getUrl();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
