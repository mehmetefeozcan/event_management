<?php

// Grid: hours (9–17) × days (1–5). Each cell holds every event overlapping that hour.
// Events that fall outside the grid are returned in 'outside'.
function buildWeeklyCalendar(array $eventList): array
{
    $hours = range(9, 17);
    $days = [1 => 'Pazartesi', 2 => 'Salı', 3 => 'Çarşamba', 4 => 'Perşembe', 5 => 'Cuma'];
    $cells = [];
    $outside = [];

    foreach ($eventList as $event) {
        $interval = eventInterval($event);
        $day = (int) date('N', $interval['start']);
        $placed = false;

        foreach ($hours as $hour) {
            $hourStart = strtotime(date('Y-m-d', $interval['start']) . sprintf(' %02d:00:00', $hour));
            $hourEnd = $hourStart + 3600;

            if (isset($days[$day]) && $interval['start'] < $hourEnd && $interval['end'] > $hourStart) {
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
