<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../../../../vendor/autoload.php";
$doctor = new Doctor;
$doctor->logoutUser("doctor");
