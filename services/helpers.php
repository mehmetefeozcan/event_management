<?php

function result(bool $success, string $message): array
{
    return ['success' => $success, 'message' => $message];
}

function findEvent(array $eventList, int $id): ?array
{
    foreach ($eventList as $event) {
        if ($event['id'] === $id) {
            return $event;
        }
    }

    return null;
}

function findStudent(array $studentList, int $id): ?array
{
    foreach ($studentList as $student) {
        if ($student['id'] === $id) {
            return $student;
        }
    }

    return null;
}

function reservationCount(array $reservations, int $eventId): int
{
    return count($reservations[$eventId] ?? []);
}

function remainingCapacity(array $event, array $reservations): int
{
    return max(0, $event['capacity'] - reservationCount($reservations, $event['id']));
}

function occupancyRate(array $event, array $reservations): float
{
    if ($event['capacity'] <= 0) {
        return 0.0;
    }

    return reservationCount($reservations, $event['id']) / $event['capacity'] * 100;
}

function occupancyClass(float $rate): string
{
    if ($rate >= 100) {
        return 'Tam dolu';
    }
    if ($rate >= 75) {
        return 'Yüksek doluluk';
    }
    if ($rate >= 25) {
        return 'Orta doluluk';
    }

    return 'Düşük doluluk';
}

function occupancyColor(float $rate): string
{
    if ($rate >= 100) {
        return 'danger';
    }
    if ($rate >= 75) {
        return 'warning';
    }
    if ($rate >= 25) {
        return 'success';
    }

    return 'info';
}

function formatEventTime(array $event): string
{
    $interval = eventInterval($event);

    return date('d.m.Y • H:i', $interval['start']) . ' - ' . date('H:i', $interval['end']);
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
