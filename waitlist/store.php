<?php
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
}

$action = $_POST['action'] ?? '';
$studentId = (int) ($_POST['student_id'] ?? 0);
$eventId = (int) ($_POST['event_id'] ?? 0);

if ($action === 'add') {
    $result = addToWaitlist($eventList, $studentList, $_SESSION['reservations'], $_SESSION['waitlist'], $studentId, $eventId);

    setFlash($result['success'] ? 'success' : 'danger', $result['message']);
    header('Location: ' . BASE_PATH . '/event/detail.php?id=' . $eventId);
    exit;
}

if ($action === 'remove') {
    removeFromWaitlist($_SESSION['waitlist'], $studentId, $eventId);

    setFlash('success', 'Öğrenci bekleme listesinden çıkarıldı.');
    header('Location: ' . BASE_PATH . '/waitlist/index.php');
    exit;
}

header('Location: ' . BASE_PATH . '/index.php');
exit;
