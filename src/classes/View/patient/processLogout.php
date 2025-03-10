<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["patient-login"])) {
    header("Location: ./index.php");
    exit();
}
require_once __DIR__ . "/../../../../vendor/autoload.php";

$patient = new Patient;
$patientState = new loggedInUser;
$patientState->removeLoggedInUser($_SESSION["user_id"]);
$patient->logoutUser("patient");
