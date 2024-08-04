<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\DataTransferObjects\EventDto;

interface EventInterface {
    public function getAllEvents(Request $request);
	public function storeEvent(EventDto $request);
    public function getEventById($id);
    public function updateEvent(EventDto $request,$id);
    public function deleteEvent($id);
}
