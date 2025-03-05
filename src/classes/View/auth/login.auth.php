<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../../handle_error/handle_error.php';
include_once __DIR__ . '/../../Models/userState.model.php';
include_once __DIR__ . '/../../../../vendor/autoload.php';


//user sign in without google
function userSignIn($email, $password, $user_type)
{
    if (empty($email) && empty($password)) {
        echo "Ensure all fields are filled!";
    } else {
        $isLoggedIn = new loggedInUser;

        // Determine the user
        if ($user_type === "patient") {
            //For the patient  -- check the user type from the database
            $patient = new Patient;

            if ($patient->authenticatePatient($email, $password)) {
                $userPresence = "available"; // Set the user presence to online
                $_SESSION["patient-login"] = true;
                $userID = $patient->getUserID($email);
                $_SESSION["patientEmail"] = $email;
                $_SESSION["logged-in-patients"][0] = $patient->getUserID($email);

                //LOGIN the patient
                if ($isLoggedIn->setLoggedInUser($userID, $email, $user_type, $userPresence)) {
                    echo '<script type="text/javascript">window.location.href = "dashboard.php";</script>';
                }
            }
        }

        //For the doctor -- check the user type from the database
        if ($user_type === "doctor") {
            $doctor = new Doctor;
            if ($doctor->authenticateDoctor($email, $password)) {
                $userPresence = "available"; // Set the user presence to online
                $_SESSION['doctor-login'] = true;
                $userID = $doctor->getUserID($email);
                $_SESSION["doctorEmail"] = $email;
                $_SESSION["logged-in-doctors"][0] = $doctor->getUserID($email);

                //LOGIN the user
                if ($isLoggedIn->setLoggedInUser($userID, $email, $user_type, $userPresence)) {
                    echo '<script type="text/javascript">window.location.href = "dashboard.php";</script>';
                }
            } else {
                $userPresence = "Offline"; // Set the user presence to offline
                echo "<br>" . handle_error("Failed to authenticate the user");
            }
        } else {
            echo "<br>" . handle_error("Failed to authenticate the user") . handle_error("You don't have the right to access this page...");
            handle_error("ACCESS DENIED!");
            echo '<script type="text/javascript"> //redirects to the patient index page
                setTimeout(()=>{
                    window.location.href = "../patient/index.php";
                }, 7000);
            </script>';
        }
    }
}

function processSignUp($user, $email, $password, $fullName, $user_type)
{
    if (empty($fullName) && empty($email) && empty($password)) {
        echo "Ensure all fields are filled!";
    } else {
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


function userSignUp($email, $password, $fullName, $user_type)
{
    if ($user_type === "patient") {
        $patient = new Patient;
        processSignUp($patient, $email, $password, $fullName, $user_type);
    } elseif ($user_type === "doctor") {
        $doctor = new Doctor;
        processSignUp($doctor, $email, $password, $fullName, $user_type);
    }
}
