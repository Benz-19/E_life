<?php

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
            echo '<script type="text/javascript">window.location.href = "../src/classes/View/patient/dashboard.patient.view.php";</script>';
        } else {
            echo "<br>" . handle_error("Failed to authenticate the user");
        }
    }
}
function userSignUp($email, $password, $fullName, $user_type)
{
    if (empty($fullName) && empty($email) && empty($password)) {
        echo "Ensure all fields are filled!";
    } else {
        $user = new Patient; //creating a new patient

        if ($user->createUserDetail($fullName, $email, $password, $user_type)) {
            echo '<script type="text/javascript">window.location.href = "../src/classes/View/patient/dashboard.patient.view.php";</script>';
        } else {
            echo "<br>" . handle_error("Failed to store your details...") . "Try again!";
        }
    }
}

//user sign in using google

function userSignUpGoogle() {}
