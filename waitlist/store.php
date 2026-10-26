<?php
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/index.php');
}

$action = $_POST['action'] ?? '';
$studentId = (int) ($_POST['student_id'] ?? 0);
$eventId = (int) ($_POST['event_id'] ?? 0);

if ($action === 'add') {
    $result = addToWaitlist($eventList, $studentList, $_SESSION['reservations'], $_SESSION['waitlist'], $studentId, $eventId);

    flashRedirect($result, '/event/detail.php?id=' . $eventId);
}

if ($action === 'remove') {
    removeFromWaitlist($_SESSION['waitlist'], $studentId, $eventId);

    flashRedirect(result(true, 'Öğrenci bekleme listesinden çıkarıldı.'), '/waitlist/index.php');
}

redirect('/index.php');
