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

    public function store(StoreCalendarEventRequest $request)
    {
        $isEmployeeAvailable = $this->checkOverlap($request->validated(), $this->getUserAssotiatedEvents());
        dd($isEmployeeAvailable);
    }

    public function index(IndexCalendarEventRequest $request): AnonymousResourceCollection
    {
        // get public holidays according user's country here and pass to resource
        return CalendarEventResource::collection($this->getUserAssotiatedEvents());
    }

    function checkOverlap($validatedData, $userEvents)
    {
        $requestedStartDate = $validatedData['start_date'];
        $requestedEndDate = $validatedData['end_date'];

        $isOverlapped = false;

        foreach ($userEvents as $event) {
            $userStartDate = $event->start_date;
            $userEndDate = $event->end_date;
            $isOverlapped = $this->isOverlapped($requestedStartDate, $requestedEndDate, $userStartDate, $userEndDate);
            if ($isOverlapped) {
                break;
            }
        }

        return $isOverlapped ? "employer is not available for chosen time section" : "stored";
    }
    function isOverlapped($requestedStartDate, $requestedEndDate, $userStartDate, $userEndDate)
    {
        if ($requestedStartDate >= $userStartDate && $requestedStartDate <= $userEndDate) {
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
