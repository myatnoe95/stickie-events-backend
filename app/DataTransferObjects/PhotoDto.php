<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PhotoDTO
{
    public ?int     $user_id;
    public ?int     $event_id;
    public          $photo_url;
    public ?string  $status;

    public function __construct(
        ?int      $user_id,
        ?int      $event_id,
                  $photo_url,
        ?string   $status
    ) {
        $this->user_id    = $user_id;
        $this->event_id   = $event_id;
        $this->photo_url  = $photo_url;
        $this->status     = $status;

        $this->validate();
    }

    public static function fromRequest(Request $request): PhotoDTO
    {
        return new self(
            $request->input('user_id'),
            $request->input('event_id'),
            $request->input('photo_url'),
            $request->input('status')
        );
    }

    public function validate()
    {
        $request = (array) $this;

        $rules = [
            'user_id'     => 'nullable|integer',
            'event_id'    => 'required|integer',
            'photo_url'   => 'required',
            'status'      => 'nullable|in:Approved,Pending,Rejected',
        ];

        $validator = Validator::make($request, $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    public function toArray(): array
    {
        return [
            'user_id'    => $this->user_id,
            'event_id'   => $this->event_id,
            'photo_url'  => $this->photo_url,
            'status'     => $this->status,
        ];
    }
}
