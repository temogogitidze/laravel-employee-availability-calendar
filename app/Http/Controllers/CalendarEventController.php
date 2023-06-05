<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexCalendarEventRequest;
use App\Http\Requests\StoreCalendarEventRequest;
use App\Http\Resources\CalendarEventResource;
use App\Models\CalendarEvent;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CalendarEventController extends Controller
{

    public function __construct(private CalendarEvent $model)
    {
    }

    public function store(StoreCalendarEventRequest $request): void
    {
        $validatedData = $request->validated();

        if ($this->isEmployeeAvailable($validatedData)) {
            // Store the event in the database
            dd('stored');
//            $this->model->create($validatedData);
        } else {
            dd('employer is not available for chosen time section');
        }
    }

    public function index(IndexCalendarEventRequest $request): AnonymousResourceCollection
    {
        // get public holidays according user's country here and pass to resource
        return CalendarEventResource::collection($this->getUserAssotiatedEvents());
    }

    function isEmployeeAvailable($validatedData): bool
    {
        $userEvents = $this->getUserAssotiatedEvents()->toArray();

        return !$this->checkOverlapRecursive($validatedData, $userEvents);
    }

    function checkOverlapRecursive($validatedData, $userEvents): bool
    {
        return array_reduce($userEvents, function ($carry, $event) use ($validatedData) {
            return $carry || $this->checkOverlap($validatedData, $event);
        }, false);
    }

    function checkOverlap($validatedData, $userEvent): bool
    {
        $requestedStartDate = $validatedData['start_date'];
        $requestedEndDate = $validatedData['end_date'];

        $userStartDate = $userEvent['start_date'];
        $userEndDate = $userEvent['end_date'];

        if ($requestedStartDate <= $userStartDate && $requestedEndDate >= $userEndDate) {
            return true;
        } elseif ($requestedEndDate >= $userStartDate && $requestedEndDate <= $userEndDate) {
            return true;
        } elseif ($requestedStartDate < $userStartDate && $requestedEndDate > $userEndDate) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserAssotiatedEvents()
    {
        return $this->model->where('user_id', 1)->get();
    }

    // update value should be converted in unix timestamp and compared in events table records for request user
//    public function update(IndexCalendarEventRequest $request): AnonymousResourceCollection
//    {
//       compare here ...
//    }
}
