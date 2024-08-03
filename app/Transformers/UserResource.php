<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                            => $this->when(isset($this->id), $this->id),
            'user_name'                     => $this->when(isset($this->user_name), $this->user_name),
			'role'                     		=> $this->when(isset($this->role), $this->role),
        ];
    }
}
