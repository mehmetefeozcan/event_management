<?php

function isRegistered(array $reservations, int $studentId, int $eventId): bool
{
    return in_array($studentId, $reservations[$eventId] ?? [], true);
}

function studentEventIds(array $reservations, int $studentId): array
{
    return array_keys(array_filter($reservations, fn($ids) => in_array($studentId, $ids, true)));
}

function evaluateReservation(array $eventList, array $studentList, array $reservations, int $studentId, int $eventId): array
{
    $event = findById($eventList, $eventId);
    $student = findById($studentList, $studentId);

    if ($event === null || $student === null) {
        return result(false, 'Öğrenci ya da etkinlik sistemde bulunamadı.');
    }

    if ($student['grade'] < $event['min_grade']) {
        return result(false, "Bu etkinlik en az {$event['min_grade']}. sınıf düzeyi gerektiriyor.");
    }

    if (remainingCapacity($event, $reservations) <= 0) {
        return result(false, 'Etkinlikte boş kontenjan kalmadı.');
    }

    if (isRegistered($reservations, $studentId, $eventId)) {
        return result(false, 'Bu etkinliğe zaten kayıtlısınız.');
    }

    if (studentHasOverlap($eventList, studentEventIds($reservations, $studentId), $event)) {
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
    if (!isRegistered($reservations, $studentId, $eventId)) {
        return result(false, 'Bu rezervasyon bulunamadı.');
    }

    $reservations[$eventId] = array_values(array_diff($reservations[$eventId], [$studentId]));

    return array_merge(
        result(true, 'Rezervasyon iptal edildi.'),
        processWaitlist($eventList, $studentList, $reservations, $waitlist, $eventId)
    );
}
