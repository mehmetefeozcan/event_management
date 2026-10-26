<?php

function addToWaitlist(array $eventList, array $studentList, array $reservations, array &$waitlist, int $studentId, int $eventId): array
{
    $event = findById($eventList, $eventId);
    $student = findById($studentList, $studentId);

    if ($event === null || $student === null) {
        return result(false, 'Öğrenci ya da etkinlik sistemde bulunamadı.');
    }

    if (remainingCapacity($event, $reservations) > 0) {
        return result(false, 'Etkinlikte boş kontenjan var. Doğrudan kayıt olabilirsiniz.');
    }

    if (isRegistered($reservations, $studentId, $eventId)) {
        return result(false, 'Bu etkinliğe zaten kayıtlısınız.');
    }

    if (in_array($studentId, $waitlist[$eventId] ?? [], true)) {
        return result(false, 'Bu öğrenci zaten bekleme listesinde.');
    }

    $waitlist[$eventId][] = $studentId;

    return result(true, 'Bekleme listesine eklendi. Sıranız: ' . count($waitlist[$eventId]) . '.');
}

function removeFromWaitlist(array &$waitlist, int $studentId, int $eventId): void
{
    $waitlist[$eventId] = array_values(array_diff($waitlist[$eventId] ?? [], [$studentId]));
}

function processWaitlist(array $eventList, array $studentList, array &$reservations, array &$waitlist, int $eventId): array
{
    $outcome = ['promoted' => [], 'rejected' => []];
    $event = findById($eventList, $eventId);

    if ($event === null) {
        return $outcome;
    }

    while (remainingCapacity($event, $reservations) > 0 && !empty($waitlist[$eventId] ?? [])) {
        $candidateId = array_shift($waitlist[$eventId]);
        $evaluation = evaluateReservation($eventList, $studentList, $reservations, $candidateId, $eventId);

        if ($evaluation['success']) {
            makeReservation($reservations, $candidateId, $eventId);
        }

        $outcome[$evaluation['success'] ? 'promoted' : 'rejected'][] = $candidateId;
    }

    return $outcome;
}
