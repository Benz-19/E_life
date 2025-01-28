<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../../handle_error/handle_error.php';
require __DIR__ . '/../../../../vendor/autoload.php';

if (isset($_POST['submit_code'])) {

    $inputCode = $_POST['verification_code'];

    if (password_verify($inputCode, $_SESSION['verificationCode'])) {
        echo success_message("Verification successful");

        $fullName = $_SESSION['fullName'];
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
        $user_type = $_SESSION['userType'];

        $user = new User;

        if ($user->createUserDetail($fullName, $email, $password, $user_type)) {
            $user->updateUserVerificationCode($email, $_SESSION['verificationCode']);
            $user->updateUserVericationStatus($email, 1);
            redirect_message("redirecting you to the login page...");
            echo '<script type="text/javascript">
            setTimeout(()=>{
                window.location.href = "../../../../public/index.php";
            }, 10000);
            </script>';
        } else {
            echo "<br>" . handle_error("Failed to store your details...") . handle_error("Try again!");
        }
    } else {
        handle_error("Invalid verification code...<br>Terminating verification process");
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <title>Document</title>
    <style>
        .loader-dots {
            display: flex;
            justify-content: center;
        }

        .dot {
            animation: loading 0.6s infinite alternate;
        }

        @keyframes loading {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.5);
            }
        }
    </style>
</head>

<body>
    <div id="loader" class="fixed inset-0 flex flex-col items-center justify-center bg-gray-100">
        <h1 class="text-lg mt-3">Loading</h1>
        <div class="loader-dots">
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
        </div>
    </div>
    <form action="" method="post">
        <div id="form-container" class="hidden flex flex-col items-center justify-center min-h-screen bg-gray-100">
            <div class="bg-white shadow-md rounded-lg p-8 transform -translate-x-full transition-transform duration-700" id="verification-form">
                <h1 class="text-xl font-semibold mb-6 text-center">Enter the Verification Code Sent To Your Email</h1>
                <input type="text" name="verification_code" class="border border-gray-300 rounded-lg py-2 px-4 w-full mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center" placeholder="Verification Code" required>
                <button type="submit" name="submit_code" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Verify Code</button>
            </div>
        </div>
    </form>

    <script>
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            const formContainer = document.getElementById('form-container');
            formContainer.classList.remove('hidden');
            document.getElementById('verification-form').style.transform = 'translateX(0)';
        }, 5000);
    </script>
</body>

</html>