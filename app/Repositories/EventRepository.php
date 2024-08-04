<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use App\Interfaces\EventInterface;
use App\Transformers\EventResource;
use App\DataTransferObjects\EventDto;

class EventRepository implements EventInterface{

    public function getAllEvents(Request $request)
    {
		$columns = ['event_name', 'description'];
        $pageIndex = $request->input('pageIndex');
        $length = $request->input('pageSize');

		$start = $length * $pageIndex - $length;
        $search = $request->input('query');

        $query = Event::query();

		if (!empty($search)) {
            $query->where(function ($q) use ($search, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        $totalRecords = $query->count();

		if (!empty($length)) {
            $query->offset($start);
        }
        $query->limit($length);

        $data = $query->get();
        $result = [
            'totalRecords'  => $totalRecords,
            'data'          => $data,
        ];

        return $result;
    }

	public function storeEvent(EventDto $request)
    {
        $event     = new Event();
        $eventDBT  = DB::transaction(function () use ($event, $request) {
            $event->fill($request->toArray())->save();
            return $event;
        });
        return $eventDBT;
    }

    public function getEventById($id)
    {
        $event = Event::where('id', $id)->first();

		return $event == null ? null : $event;
    }

	public function updateEvent(EventDto $request, $id)
    {
        $event = Event::find($id);

        if ($event == null) {
            return null;
        }

        $eventDBT = DB::transaction(function () use ($event, $request) {
            $event->fill($request->toArray())->save();
            return $event;
        });

        return $eventDBT;
    }

    public function deleteEvent($id)
    {
		$event = Event::find($id);

		if ($event == null) {
			return null;
		}

		$eventDBT = DB::transaction(function () use ($event) {
			$event->delete();
			return $event;
		});

		return $eventDBT;
    }
}
