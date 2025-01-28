<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../../handle_error/handle_error.php';
include_once __DIR__ . '/../../../../vendor/autoload.php';


// Path: src/classes/View/auth/login.auth.php
//user sign in without google
function userSignIn($email, $password)
{
    if (empty($email) && empty($password)) {
        echo "Ensure all fields are filled!";
    } else {
        $user = new User; //creating a new user (patient/doctor)

        if ($user->authenticateUser($email, $password)) {
            echo '<script type="text/javascript">window.location.href = "../src/classes/View/patient/dashboard.php";</script>';
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
        $user = new User; //creating a new user (patient/doctor)
        $user->verifyUserEmail($fullName, $email);
        $_SESSION['verificationCode'] = $user->getEmailVerificationCode();

        $_SESSION['currentUser'] = $user;

        $_SESSION['fullName'] = $fullName;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['userType'] = $user_type;
        echo '<script type="text/javascript">window.location.href = "../src/classes/View/auth/verify.auth.php";</script>'; //redirect from index.php to verify page
    }
}
