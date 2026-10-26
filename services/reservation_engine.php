<?php

function studentEventIds(array $reservations, int $studentId): array
{
    $eventIds = [];

    foreach ($reservations as $eventId => $studentIds) {
        if (in_array($studentId, $studentIds, true)) {
            $eventIds[] = $eventId;
        }
    }

    return $eventIds;
}

function evaluateReservation(array $eventList, array $studentList, array $reservations, int $studentId, int $eventId): array
{
    $event = findEvent($eventList, $eventId);
    $student = findStudent($studentList, $studentId);

    if ($event === null || $student === null) {
        return result(false, 'Öğrenci ya da etkinlik sistemde bulunamadı.');
    }

    if ($student['grade'] < $event['min_grade']) {
        return result(false, "Bu etkinlik en az {$event['min_grade']}. sınıf düzeyi gerektiriyor.");
    }

    if (remainingCapacity($event, $reservations) <= 0) {
        return result(false, 'Etkinlikte boş kontenjan kalmadı.');
    }

    if (in_array($studentId, $reservations[$eventId] ?? [], true)) {
        return result(false, 'Bu etkinliğe zaten kayıtlısınız.');
    }

    $studentEvents = studentEventIds($reservations, $studentId);

    if (studentHasOverlap($eventList, $studentEvents, $event)) {
        return result(false, 'Bu etkinlik, mevcut bir rezervasyonunuzla zaman çakışıyor.');
    }

    return result(true, 'Rezervasyonunuz oluşturuldu.');
}

function makeReservation(array &$reservations, int $studentId, int $eventId): void
{
    $reservations[$eventId][] = $studentId;
}

function cancelReservation(array $eventList, array $studentList, array &$reservations, array &$waitlist, int $studentId, int $eventId): array
{
    if (!in_array($studentId, $reservations[$eventId] ?? [], true)) {
        return result(false, 'Bu rezervasyon bulunamadı.');
    }

    $reservations[$eventId] = array_values(array_diff($reservations[$eventId], [$studentId]));

    $waitlistResult = processWaitlist($eventList, $studentList, $reservations, $waitlist, $eventId);

    return array_merge(result(true, 'Rezervasyon iptal edildi.'), $waitlistResult);
}
