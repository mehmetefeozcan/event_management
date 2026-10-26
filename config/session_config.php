<?php
date_default_timezone_set('Europe/Istanbul');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// reservations[etkinlik_id] = [ogrenci_id, ...]
// waitlist[etkinlik_id]     = [ogrenci_id, ...]  (başvuru sırasına göre)
$_SESSION['reservations'] ??= [];
$_SESSION['waitlist'] ??= [];
