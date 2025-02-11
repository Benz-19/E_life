<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../../handle_error/handle_error.php';
include_once __DIR__ . '/../../Models/userState.model.php';
include_once __DIR__ . '/../../../../vendor/autoload.php';


// Path: src/classes/View/auth/login.auth.php
//user sign in without google
function userSignIn($email, $password, $user_type)
{
    if (empty($email) && empty($password)) {
        echo "Ensure all fields are filled!";
    } else {
        $user = new User; //creating a new user (patient/doctor)
        $isLoggedIn = new loggedInUser;

        if ($user->authenticateUser($email, $password)) {

            $userPresence = "available"; // Set the user presence to online

            // Determine the user

            if ($user_type === "patient") { //For the patient  -- check the user type from the database

                $_SESSION["patient-login"] = true;
                $userID = $user->getUserID($email);
                $_SESSION["patientEmail"] = $email;
                $_SESSION["logged-in-patients"][0] = $user->getUserID($email);

                //LOGIN the patient
                if ($isLoggedIn->setLoggedInUser($userID, $email, $user_type, $userPresence)) {
                    echo '<script type="text/javascript">window.location.href = "dashboard.php";</script>';
                }
            } elseif ($user_type === "doctor") { //For the doctor -- check the user type from the database
                session_start();
                $_SESSION['doctor-login'] = true;
                $userID = $user->getUserID($email);
                $_SESSION["doctorEmail"] = $email;
                $_SESSION["logged-in-doctors"][0] = $user->getUserID($email);

                //LOGIN the user
                if ($isLoggedIn->setLoggedInUser($userID, $email, $user_type, $userPresence)) {
                    echo '<script type="text/javascript">window.location.href = "dashboard.php";</script>';
                }
            } else {
                echo "<br>" . handle_error("Failed to authenticate the user") . handle_error("You don't have the right to access this page...");
                handle_error("ACCESS DENIED!");
            }
        } else {
            $userPresence = "Offline"; // Set the user presence to offline
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

        if ($user_type === "patient") {
            echo '<script type="text/javascript">window.location.href = "../src/classes/View/auth/verify.auth.php";</script>'; //redirect from index.php to verify page
        } elseif ($user_type === "doctor") {
            echo '<script type="text/javascript">window.location.href = "../auth/verify.auth.php";</script>'; //redirect from index.php to verify page
        }
    }
}
