<?php

function buildEventAnalytics(array $eventList, array $reservations, array $waitlist): array
{
    $analytics = [];

    foreach ($eventList as $event) {
        $analytics[] = [
            'id' => $event['id'],
            'name' => $event['name'],
            'capacity' => $event['capacity'],
            'count' => reservationCount($reservations, $event['id']),
            'rate' => occupancyRate($event, $reservations),
            'waiting' => count($waitlist[$event['id']] ?? []),
        ];
    }

    return $analytics;
}

function countAll(array $lists): int
{
    $total = 0;

    foreach ($lists as $items) {
        $total += count($items);
    }

    return $total;
}

function mostDemanded(array $analytics): ?array
{
    $selected = null;

    foreach ($analytics as $row) {
        if ($selected === null || $row['count'] > $selected['count']) {
            $selected = $row;
        }
    }

    return $selected;
}

function leastDemanded(array $analytics): ?array
{
    $selected = null;

    foreach ($analytics as $row) {
        if ($selected === null || $row['count'] < $selected['count']) {
            $selected = $row;
        }
    }

    return $selected;
}

function occupancyClassCounts(array $analytics): array
{
    $classes = array_fill_keys(array_column(OCCUPANCY_LEVELS, 'label'), 0);

    foreach ($analytics as $row) {
        $classes[occupancyClass($row['rate'])]++;
    }

    return $classes;
}

function gradeDistribution(array $studentList, array $reservations): array
{
    $distribution = [];

    foreach ($reservations as $studentIds) {
        foreach (findStudents($studentList, $studentIds) as $student) {
            $distribution[$student['grade']] = ($distribution[$student['grade']] ?? 0) + 1;
        }
    }

    ksort($distribution);

    return $distribution;
}
