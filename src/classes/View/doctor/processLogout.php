<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["doctor-login"])) {
    header("Location: ./index.doctor.php");
    exit();
}

require_once __DIR__ . "/../../../../vendor/autoload.php";
$doctor = new Doctor;
$doctorState = new loggedInUser;
$doctorState->removeLoggedInUser($_SESSION["user_id"]);
$doctor->logoutUser("doctor");
