<?php
// filepath: /C:/xampp/htdocs/E_life/src/classes/View/auth/login.auth.php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../Models/userState.model.php';
include_once __DIR__ . '/../../../../src/handle_error/handle_error.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../../');
$dotenv->load();


function getGoogleClient()
{
    $client = new Google_Client();
    $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
    $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
    $client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
    $client->addScope("email");
    $client->addScope("profile");
    return $client;
}

function handleGoogleUser($google_id, $name, $email, $google_verified, $google_verification_code)
{
    $user = new User();
    $isLoggedIn = new loggedInUser;
    $userPresence = "online";

    $existingUser = $user->getUserByGoogleId($google_id);

    if ($existingUser) {
        $user->updateUserDetailGoogle($existingUser['id'], $name, $email, $google_verified, $google_verification_code);
    } else {
        $user->createUserDetailGoogle($name, $email, $google_verified, $google_verification_code, $google_id, $_SESSION["user_type"]);
        echo "session: " . $_SESSION["user_type"];
    }

    $_SESSION['user_id'] = $existingUser ? $existingUser['id'] : $user->getLastInsertId();

    if ($_SESSION["user_type"] === "patient") {

        $_SESSION["patient-login"] = true;
        $userID = $user->getUserID($email);
        $_SESSION["patientEmail"] = $email;
        $_SESSION["logged-in-patients"][0] = $user->getUserID($email);

        //LOGIN the patient
        if ($isLoggedIn->setLoggedInUser($userID, $email, $_SESSION["user_type"], $userPresence)) {
            echo '<script type="text/javascript">
            alert("Welcome! Redirecting to the dashboard...");
            window.location.href = "../../../../public/../src/classes/View/patient/dashboard.php";
          </script>';
        }
    } elseif ($_SESSION["user_type"] === "doctor") {

        $_SESSION["doctor-login"] = true;
        $userID = $user->getUserID($email);
        $_SESSION["doctorEmail"] = $email;
        $_SESSION["logged-in-doctors"][0] = $user->getUserID($email);

        //LOGIN the user
        if ($isLoggedIn->setLoggedInUser($userID, $email, $_SESSION["user_type"], $userPresence)) {
            echo '<script type="text/javascript">
            alert("Welcome! Redirecting to the dashboard...");
            window.location.href = "../doctor/index.doctor.php";
          </script>';
        }
    } else {
        echo handle_error("User type not found");
        exit();
    }
    exit();
}


$client = getGoogleClient();

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        echo handle_error("Error fetching access token: " . $token['error_description']);
        exit();
    }

    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $google_id = $google_account_info->id;
    $google_verified = $google_account_info->verified_email;

    $google_verification_code = $token['access_token'];
    $verification_state = $google_verified ? 'verified' : 'unverified';

    handleGoogleUser($google_id, $name, $email, $google_verified, $google_verification_code);
} else {
    $authUrl = $client->createAuthUrl();
    echo '<script type="text/javascript">window.location.href="' . $authUrl . '";</script>';
    exit();
}
