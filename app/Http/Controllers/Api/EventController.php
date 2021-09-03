<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventDatesService;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function index(EventDatesService $eventDatesService)
    {
        $events = Event::with('event_dates')->get();

        // format into calendar data and flatten the dates
        $formattedCalendarData = [];
        foreach ($events as $event) {
            $formattedCalendarData = array_merge($formattedCalendarData, $eventDatesService->formatCalendarData($event));
        }

        return $formattedCalendarData;
    }

    public function store(StoreEventRequest $request, EventDatesService $eventDatesService)
    {
        // validate input
        $validatedData = $request->validated();

        // get day codes
        $days = $eventDatesService->getDayCodes($validatedData['days']);

        // remove days key before creating new event
        unset($validatedData['days']);

        // create new event
        $event = Event::create($validatedData);

        // get all dates between two dates
        // then save each date
        foreach ($days as $day) {
            $dates = $eventDatesService->getDaysBetweenTwoDates($day, $validatedData['start_date'], $validatedData['end_date']);

            foreach ($dates as $date) {
                $event->event_dates()->create([
                    'date' => $date
                ]);
            }
        }

        // format into calendar data
        $formattedCalendarData = $eventDatesService->formatCalendarData($event);

        return new JsonResponse($formattedCalendarData, 201);
    }
}
