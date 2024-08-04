<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'event_name'                     => $this->when(isset($this->event_name), $this->event_name),
			'description'                    => $this->when(isset($this->description), $this->description),
			'date'                    		 => $this->when(isset($this->date), $this->date),
			'time'                           => $this->when(isset($this->time), $this->time),
			'location'                       => $this->when(isset($this->location), $this->location),
        ];
    }
}
