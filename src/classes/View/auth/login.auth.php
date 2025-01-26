<?php

use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

include_once __DIR__ . '/../../../handle_error/handle_error.php';
include_once __DIR__ . '/../../../../vendor/autoload.php';

// Path: src/classes/View/auth/login.auth.php
//user sign in without google
function userSignIn($email, $password)
{
    if (empty($email) && empty($password)) {
        echo "Ensure all fields are filled!";
    } else {
        $user = new Patient; //creating a new patient

        if ($user->authenticateUser($email, $password)) {
            echo '<script type="text/javascript">window.location.href = "yes.php";</script>';
        } else {
            echo "<br>" . handle_error("Failed to authenticate the user");
        }
    }
}
function userSignUp($email, $password, $fullName)
{
    if (empty($fullName) && empty($email) && empty($password)) {
        echo "Ensure all fields are filled!";
    } else {
        $user = new Patient; //creating a new patient

        if ($user->createUserDetail($fullName, $email, $password)) {
            echo '<script type="text/javascript">window.location.href = "yes.php";</script>';
        } else {
            echo "<br>" . handle_error("Failed to store your details...") . "Try again!";
        }
    }
}

//user sign in using google
function userSignInGoogle()
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../../');
    $dotenv->load();


    // init configuration
    $clientID = $_ENV['GOOGLE_CLIENT_ID'];
    $clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
    $redirectUri = $_ENV['GOOGLE_REDIRECT_URI'];

    // create Client Request to access Google API
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

    // authenticate code from Google OAuth Flow
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // Check for errors in the token response
        if (isset($token['error'])) {
            echo handle_error("Error fetching access token: " . $token['error_description']);
            exit();
        }

        if (!isset($token['access_token'])) {
            echo handle_error("Access token not found in the response.");
            exit();
        }

        $client->setAccessToken($token['access_token']);

        // get profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email =  $google_account_info->email;
        $name =  $google_account_info->name;
        $google_id =  $google_account_info->id;
        $google_verified =  $google_account_info->verified_email;

        // Assign variables for Google ID, Google verification code, and verification state
        $google_verification_code = $token['access_token']; // Assuming the access token is used as the verification code
        $verification_state = $google_verified ? 'verified' : 'unverified';

        // now you can use this profile info to create account in your website and make user logged in.
        $user = new User();
        $existingUser = $user->getUserByGoogleId($google_id);

        if ($existingUser) {
            // User already exists, update their information if necessary
            $user->updateUserDetailGoogle($existingUser['id'], $name, $email, $google_verified, $google_verification_code);
            echo '<script type="text/javascript">window.location="yes.php";</script>';
        } else {
            // Create a new user
            $user->createUserDetailGoogle($name, $email, $google_verified, $google_verification_code, $google_id);
            echo '<script type="text/javascript">window.location="yes.php";</script>';
        }

        // Log the user in (you might want to set session variables or cookies here)
        $_SESSION['user_id'] = $existingUser ? $existingUser['id'] : $user->getLastInsertId();
        echo '<script type="text/javascript">window.location="yes.php";</script>';
        // header('Location: dashboard.php'); // Redirect to a dashboard or home page
        exit();
    } else {
        echo "<a href='" . $client->createAuthUrl() . "'>Google Login</a>";

        echo handle_error("Authorization code not found.");
    }
}


function userSignUpGoogle() {}
