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
        $validatedData = $request->validated();

        $userEvents = $this->getUserAssotiatedEvents();
        $requestedStartDate = $validatedData['start_date'];
        $requestedEndDate = $validatedData['end_date'];

        foreach ($userEvents as $event) {
            $userStartDate = $event->start_date;
            $userEndDate = $event->end_date;

            if ($requestedStartDate >= $userStartDate && $requestedStartDate <= $userEndDate) {
                // Requested start date falls within the user's booked time range
                dd('employer is not available for chosen time section');
            } elseif ($requestedEndDate >= $userStartDate && $requestedEndDate <= $userEndDate) {
                // Requested end date falls within the user's booked time range
                dd('employer is not available for chosen time section');
            } elseif ($requestedStartDate < $userStartDate && $requestedEndDate > $userEndDate) {
                // Requested start and end dates envelop the user's booked time range
                dd('employer is not available for chosen time section');
            } else {
                // Requested dates are outside the user's booked time range
                dd('stored succesfully');
            }
        }
        return $this->model->create($validatedData);
    }

    public function index(IndexCalendarEventRequest $request): AnonymousResourceCollection
    {
        // get public holidays according user's country here and pass to resource
        return CalendarEventResource::collection($this->getUserAssotiatedEvents());
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
