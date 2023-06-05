<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexCalendarEventRequest;
use App\Http\Requests\StoreCalendarEventRequest;
use App\Http\Resources\CalendarEventResource;
use App\Models\CalendarEvent;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CalendarEventController extends Controller
{

    public function __construct(private CalendarEvent $model)
    {
    }

    public function store(StoreCalendarEventRequest $request)
    {
        $validatedData = $request->validated();

        if ($this->isEmployeeAvailable($validatedData)) {
            // Store the event in the database
            dd('stored');
        } else {
            dd('employer is not available for the chosen time section');
        }
    }

    public function index(IndexCalendarEventRequest $request): AnonymousResourceCollection
    {
        // get public holidays according user's country here and pass to resource
        return CalendarEventResource::collection($this->getUserAssotiatedEvents());
    }

    public function isEmployeeAvailable($validatedData)
    {
        $userId = 1;
        $requestedStartDate = $validatedData['start_date'];
        $requestedEndDate = $validatedData['end_date'];

        $overlapCount = DB::select("
        SELECT COUNT(*) AS overlap_count
        FROM calendar_events
        WHERE user_id = :userId
        AND (
            (start_date <= :endDate AND end_date >= :startDate)
            OR (end_date >= :startDate1 AND end_date <= :endDate1)
            OR (start_date < :startDate2 AND end_date > :endDate2)
        )", [
            'userId' => $userId,
            'startDate' => $requestedStartDate,
            'endDate' => $requestedEndDate,
            'startDate1' => $requestedStartDate,
            'endDate1' => $requestedEndDate,
            'startDate2' => $requestedStartDate,
            'endDate2' => $requestedEndDate,
        ]);
        dd($overlapCount);
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
