<?php
session_start();
include_once __DIR__ . "/../../../handle_error/handle_error.php";
require __DIR__ . "/../../../../vendor/autoload.php";

if (!isset($_SESSION["doctorEmail"])) {
    echo handle_error("Failed to provide more details.") . "<br>" . handle_error("Logging you out...");
} else {
    $loggedIn = user 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>