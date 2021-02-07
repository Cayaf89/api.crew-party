<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventChoice extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request) {
        /** @var \App\Models\EventChoice $this */
        return [
            'id'             => $this->id,
            'date'           => $this->date,
            'event'          => $this->whenLoaded('event', function () {
                return $this->event;
            }),
            'users'          => $this->whenLoaded('users', function () {
                return $this->users;
            }),
            'is_user_choice' => !empty($this->is_user_choice),
            'countUsers'     => $this->countUsers(),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
