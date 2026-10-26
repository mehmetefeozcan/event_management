<?php
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
}

$action = $_POST['action'] ?? '';
$studentId = (int) ($_POST['student_id'] ?? 0);
$eventId = (int) ($_POST['event_id'] ?? 0);

if ($action === 'create') {
    $evaluation = evaluateReservation($eventList, $studentList, $_SESSION['reservations'], $studentId, $eventId);

    if ($evaluation['success']) {
        makeReservation($_SESSION['reservations'], $studentId, $eventId);
    }

    setFlash($evaluation['success'] ? 'success' : 'danger', $evaluation['message']);
    header('Location: ' . BASE_PATH . '/event/detail.php?id=' . $eventId);
    exit;
}

if ($action === 'cancel') {
    $result = cancelReservation($eventList, $studentList, $_SESSION['reservations'], $_SESSION['waitlist'], $studentId, $eventId);
    $message = $result['message'];

    if (!empty($result['promoted'] ?? [])) {
        $names = array_map(fn($id) => findStudent($studentList, $id)['name'], $result['promoted']);
        $message .= ' Bekleme listesinden kaydedilen: ' . implode(', ', $names) . '.';
    }

    setFlash($result['success'] ? 'success' : 'danger', $message);
    header('Location: ' . BASE_PATH . '/reservation/index.php');
    exit;
}

header('Location: ' . BASE_PATH . '/index.php');
exit;
