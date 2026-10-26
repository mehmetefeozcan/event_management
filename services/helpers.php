<?php

const OCCUPANCY_LEVELS = [
    ['min' => 100, 'label' => 'Tam dolu', 'color' => 'danger'],
    ['min' => 75, 'label' => 'Yüksek doluluk', 'color' => 'warning'],
    ['min' => 25, 'label' => 'Orta doluluk', 'color' => 'success'],
    ['min' => 0, 'label' => 'Düşük doluluk', 'color' => 'info'],
];

function result(bool $success, string $message): array
{
    return ['success' => $success, 'message' => $message];
}

function findById(array $items, int $id): ?array
{
    foreach ($items as $item) {
        if ($item['id'] === $id) {
            return $item;
        }
    }

    return null;
}

function findStudents(array $studentList, array $ids): array
{
    return array_values(array_filter(array_map(fn($id) => findById($studentList, $id), $ids)));
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

function occupancyLevel(float $rate): array
{
    foreach (OCCUPANCY_LEVELS as $level) {
        if ($rate >= $level['min']) {
            return $level;
        }
    }

    return OCCUPANCY_LEVELS[count(OCCUPANCY_LEVELS) - 1];
}

function occupancyClass(float $rate): string
{
    return occupancyLevel($rate)['label'];
}

function occupancyColor(float $rate): string
{
    return occupancyLevel($rate)['color'];
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

function redirect(string $path): void
{
    header('Location: ' . BASE_PATH . $path);
    exit;
}

function flashRedirect(array $result, string $path): void
{
    setFlash($result['success'] ? 'success' : 'danger', $result['message']);
    redirect($path);
}
