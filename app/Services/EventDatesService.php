<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Event;

class EventDatesService
{
    public function getDaysBetweenTwoDates($day, $startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $startDate
            ->sub("1 Day") // subtract 1 day to include current $day if $startDate = $day
            ->next($day);

        $days = [];
        for ($date = $startDate; $date->lessThanOrEqualTo($endDate); $date->addWeek()) {
            $days[] = $date->format('Y-m-d');
        }

        return $days;
    }

    public function getDayCodes($days) {
        $codes = [];
        foreach ($days as $day) {
            switch ($day) {
                case 'Sun':
                    $codes[] = 0;
                    break;
                case 'Mon':
                    $codes[] = 1;
                    break;
                case 'Tue':
                    $codes[] = 2;
                    break;
                case 'Wed':
                    $codes[] = 3;
                    break;
                case 'Thu':
                    $codes[] = 4;
                    break;
                case 'Fri':
                    $codes[] = 5;
                    break;
                case 'Sat':
                    $codes[] = 6;
                    break;
            }
        }

        return $codes;
    }

    public function formatCalendarData($event) {
        $dates = [];
        foreach ($event->event_dates as $date) {
            $dates[] = [
                'name' => $event->name,
                'start' => $date->date,
                'end' => $date->date
            ];
        }

        return $dates;
    }
}
