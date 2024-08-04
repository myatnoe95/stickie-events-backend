<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EventDTO
{
    public ?int    $id;
    public ?string $event_name;
    public ?string $description;
    public ?string $date;
    public ?string $time;
    public ?string $location;

    public function __construct(
        ?int    $id,
        ?string $event_name,
        ?string $description,
        ?string $date,
        ?string $time,
        ?string $location
    ) {
        $this->id          = $id;
        $this->event_name  = $event_name;
        $this->description = $description;
        $this->date        = $date;
        $this->time        = $time;
        $this->location    = $location;

        $this->validate();
    }

    public static function fromRequest(Request $request): EventDTO
    {
        return new self(
            $request->input('id'),
            $request->input('event_name'),
            $request->input('description'),
            $request->input('date'),
            $request->input('time'),
            $request->input('location')
        );
    }

    public function validate()
    {
        $request = (array) $this;

        $rules = [
            'id'            => 'nullable|integer',
            'event_name'    => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'date'          => 'nullable|date',
            'time'          => 'nullable|date_format:H:i',
            'location'      => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request, $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'event_name'  => $this->event_name,
            'description' => $this->description,
            'date'        => $this->date,
            'time'        => $this->time,
            'location'    => $this->location,
        ];
    }
}
