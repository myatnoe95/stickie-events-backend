<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseCodes;
use App\Interfaces\EventInterface;
use App\DataTransferObjects\EventDto;
use App\Transformers\EventResource;
use App\Transformers\EventCollection;

class EventController extends Controller
{
    private EventInterface $eventRepository;

    public function __construct(EventInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function index(Request $request)
    {
        $events = $this->eventRepository->getAllEvents($request);

        if($events == null){
            return $this->sendError($events);
        }else{
            return $this->sendResponse(
                new EventCollection($events['data']),
                ResponseCodes::OK,
                [
                    'total_records' => $events['totalRecords']
                ]
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $eventDTO = EventDto::fromRequest($request);
            $event = $this->eventRepository->storeEvent($eventDTO);

            return $this->sendResponse(
                new EventResource($event),
                ResponseCodes::CREATED
            );
        } catch (ValidationException $e) {
            return $this->sendError($e->errors(), ResponseCodes::UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage(), ResponseCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $event = $this->eventRepository->getEventById($id);
 
        if ($event == null) {
            return $this->sendError($event);
        }
        return $this->sendResponse(new EventResource($event));
    }

    public function update(Request $request, $id)
    {
        try {
            $eventDTO = EventDto::fromRequest($request,true);
            $event = $this->eventRepository->updateEvent($eventDTO, $id);

            if ($event == null) {
                return $this->sendError($event);
            }
            return $this->sendResponse(new EventResource($event),
            ResponseCodes::CREATED,[
                'message' =>  "Event data updated successfully!"
            ]);
        }
        catch (ValidationException $e) {
            return $this->sendError($e->errors(), ResponseCodes::UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage(), ResponseCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $event = $this->eventRepository->deleteEvent($id);
        if ($event == null) {
            return $this->sendError($event);
        }
        return $this->sendResponse(new EventResource($event));
    }

}
