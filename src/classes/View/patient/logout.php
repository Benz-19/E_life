<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../../../../vendor/autoload.php";
$patient = new Doctor;
$patient->logoutUser("doctor");
