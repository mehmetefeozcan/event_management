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

function totalReservations(array $reservations): int
{
    $total = 0;

    foreach ($reservations as $studentIds) {
        $total += count($studentIds);
    }

    return $total;
}

function totalWaiting(array $waitlist): int
{
    $total = 0;

    foreach ($waitlist as $studentIds) {
        $total += count($studentIds);
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
    $classes = [
        'Tam dolu' => 0,
        'Yüksek doluluk' => 0,
        'Orta doluluk' => 0,
        'Düşük doluluk' => 0,
    ];

    foreach ($analytics as $row) {
        $classes[occupancyClass($row['rate'])]++;
    }

    return $classes;
}

// Registrations per grade level.
function gradeDistribution(array $studentList, array $reservations): array
{
    $distribution = [];

    foreach ($reservations as $studentIds) {
        foreach ($studentIds as $studentId) {
            $student = findStudent($studentList, $studentId);

            if ($student === null) {
                continue;
            }

            $grade = $student['grade'];
            $distribution[$grade] = ($distribution[$grade] ?? 0) + 1;
        }
    }

    ksort($distribution);

    return $distribution;
}
