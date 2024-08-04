<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                             => $this->when(isset($this->id), $this->id),

			'user_id'                        => $this->when(isset($this->user_id), $this->user_id),
            'user'                           => new UserResource($this->whenLoaded('user')),
            
            'event_id'                       => $this->when(isset($this->event_id), $this->event_id),
            'event'                          => new EventResource($this->whenLoaded('event')),
            'photo_url'                      => $this->when(isset($this->photo_url), $this->photo_url),
            'status'                         => $this->when(isset($this->status), $this->status),
        ];
    }
}
