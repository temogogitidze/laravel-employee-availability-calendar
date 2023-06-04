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

        return $this->model->create($validatedData);
    }

    public function index(IndexCalendarEventRequest $request): AnonymousResourceCollection
    {
        // get public holidays according user's country here and pass to resource
        return CalendarEventResource::collection($this->model->where('user_id', 1)->get());
    }

    // update value should be converted in unix timestamp and compared in events table records for request user
//    public function update(IndexCalendarEventRequest $request): AnonymousResourceCollection
//    {
//       compare here ...
//    }
}
