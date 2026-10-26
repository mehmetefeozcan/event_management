<?php
date_default_timezone_set('Europe/Istanbul');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['reservations'] ??= [];
$_SESSION['waitlist'] ??= [];
