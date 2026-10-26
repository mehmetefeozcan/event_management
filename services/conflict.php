<?php

function eventInterval(array $event): array
{
    $start = strtotime($event['date']);

    return [
        'start' => $start,
        'end' => $start + $event['duration'] * 3600,
    ];
}

function isOverlapping(array $a, array $b): bool
{
    $intervalA = eventInterval($a);
    $intervalB = eventInterval($b);

    return $intervalA['start'] < $intervalB['end']
        && $intervalB['start'] < $intervalA['end'];
}

function studentHasOverlap(array $eventList, array $studentEventIds, array $newEvent): bool
{
    foreach ($studentEventIds as $eventId) {
        $existing = findEvent($eventList, $eventId);

        if ($existing !== null && isOverlapping($existing, $newEvent)) {
            return true;
        }
    }

    return false;
}
