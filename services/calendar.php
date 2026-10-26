<?php

function buildWeeklyCalendar(array $eventList): array
{
    $hours = range(9, 18);
    $days = [1 => 'Pazartesi', 2 => 'Salı', 3 => 'Çarşamba', 4 => 'Perşembe', 5 => 'Cuma'];
    $cells = [];
    $outside = [];

    foreach ($eventList as $event) {
        $interval = eventInterval($event);
        $day = (int) date('N', $interval['start']);
        $dayStart = strtotime('midnight', $interval['start']);
        $placed = false;

        foreach ($hours as $hour) {
            $hourStart = $dayStart + $hour * 3600;

            if (isset($days[$day]) && $interval['start'] < $hourStart + 3600 && $interval['end'] > $hourStart) {
                $cells[$hour][$day][] = $event;
                $placed = true;
            }
        }

        if (!$placed) {
            $outside[] = $event;
        }
    }

    return [
        'hours' => $hours,
        'days' => $days,
        'cells' => $cells,
        'outside' => $outside,
    ];
}
