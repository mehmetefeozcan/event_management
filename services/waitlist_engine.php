<?php

function addToWaitlist(array $eventList, array $studentList, array $reservations, array &$waitlist, int $studentId, int $eventId): array
{
    $event = findEvent($eventList, $eventId);
    $student = findStudent($studentList, $studentId);

    if ($event === null || $student === null) {
        return result(false, 'Öğrenci ya da etkinlik sistemde bulunamadı.');
    }

    if (remainingCapacity($event, $reservations) > 0) {
        return result(false, 'Etkinlikte boş kontenjan var. Doğrudan kayıt olabilirsiniz.');
    }

    if (in_array($studentId, $reservations[$eventId] ?? [], true)) {
        return result(false, 'Bu etkinliğe zaten kayıtlısınız.');
    }

    if (in_array($studentId, $waitlist[$eventId] ?? [], true)) {
        return result(false, 'Bu öğrenci zaten bekleme listesinde.');
    }

    $waitlist[$eventId][] = $studentId;
    $position = count($waitlist[$eventId]);

    return result(true, "Bekleme listesine eklendi. Sıranız: {$position}.");
}

function removeFromWaitlist(array &$waitlist, int $studentId, int $eventId): void
{
    if (!isset($waitlist[$eventId])) {
        return;
    }

    $waitlist[$eventId] = array_values(array_diff($waitlist[$eventId], [$studentId]));
}

// Candidates are evaluated in queue order. A candidate who no longer
// qualifies is removed from the queue and the next one is evaluated.
function processWaitlist(array $eventList, array $studentList, array &$reservations, array &$waitlist, int $eventId): array
{
    $event = findEvent($eventList, $eventId);
    $promoted = [];
    $rejected = [];

    if ($event === null) {
        return ['promoted' => $promoted, 'rejected' => $rejected];
    }

    while (remainingCapacity($event, $reservations) > 0 && !empty($waitlist[$eventId] ?? [])) {
        $candidateId = array_shift($waitlist[$eventId]);
        $evaluation = evaluateReservation($eventList, $studentList, $reservations, $candidateId, $eventId);

        if ($evaluation['success']) {
            makeReservation($reservations, $candidateId, $eventId);
            $promoted[] = $candidateId;
        } else {
            $rejected[] = $candidateId;
        }
    }

    return ['promoted' => $promoted, 'rejected' => $rejected];
}
