<?php
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/index.php');
}

$action = $_POST['action'] ?? '';
$studentId = (int) ($_POST['student_id'] ?? 0);
$eventId = (int) ($_POST['event_id'] ?? 0);

if ($action === 'create') {
    $result = evaluateReservation($eventList, $studentList, $_SESSION['reservations'], $studentId, $eventId);

    if ($result['success']) {
        makeReservation($_SESSION['reservations'], $studentId, $eventId);
    }

    flashRedirect($result, '/event/detail.php?id=' . $eventId);
}

if ($action === 'cancel') {
    $result = cancelReservation($eventList, $studentList, $_SESSION['reservations'], $_SESSION['waitlist'], $studentId, $eventId);

    if (!empty($result['promoted'])) {
        $names = array_column(findStudents($studentList, $result['promoted']), 'name');
        $result['message'] .= ' Bekleme listesinden kaydedilen: ' . implode(', ', $names) . '.';
    }

    flashRedirect($result, '/reservation/index.php');
}

redirect('/index.php');
