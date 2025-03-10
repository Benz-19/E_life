<?php
session_start();
require __DIR__ . "/../../../../vendor/autoload.php";

$user_type = "doctor";

$_SESSION["user_type"] = $user_type;

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    include_once __DIR__ . "/../auth/login.auth.php";
    // Sign in
    if (isset($_POST["sign-in-btn"])) {
        $email = $_POST["signInEmail"];
        $password = $_POST["signInPassword"];

        userSignIn($email, $password, $user_type);
    }

    // Sign up
    if (isset($_POST['sign-up-btn'])) {
        $email = $_POST['signUpEmail'];
        $fullName = $_POST['signUpFullName'];
        $password = $_POST['signUpPassword'];

        userSignUp($email, $password, $fullName, $user_type);
    }

    // Google Auth
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['google-sign-in-btn']) || isset($_POST['google-sign-up-btn'])) {
            include_once __DIR__ . "/../auth/login.googleAuth.php";

            $google_client = getGoogleClient();
            $authUrl = $google_client->createAuthUrl();
            echo '<script type="text/javascript">window.location.href = "' . $authUrl . '";</script>';
            exit();
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=600, initial-scale=1">
    <title>Sign in || Sign up from</title>
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../../public/css/index.css">
    <link rel="stylesheet" href="../../../css/styles.css">

</head>

<body>
    <!-- Loading -->
    <div id="loader" class="fixed inset-0 flex flex-col items-center justify-center bg-gray-100">
        <h1 class="text-lg mt-3 flex flex-col items-center justify-center">Loading</h1>
        <div class="loader-dots">
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
        </div>
    </div>

    <div class="form-contain">
        <div class="container-main" id="container-main">
            <div class="form-container sign-up-container">
                <form action="#" method="POST">
                    <h1>Create Account</h1>
                    <div class="social-container">
                        <button type="submit" name="facebook-sign-up-btn" class="social-button">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button type="submit" name="google-sign-up-btn" class="social-button">
                            <i class="fab fa-google-plus-g"></i>
                        </button>
                        <button type="submit" name="linkedin-sign-up-btn" class="social-button">
                            <i class="fab fa-linkedin-in"></i>
                        </button>
                    </div>
                    <span>or use your email for registration</span>
                    <div class="infield">
                        <input type="text" placeholder="Full Name" name="signUpFullName" autocomplete="off" />
                        <label></label>
                    </div>
                    <div class="infield">
                        <input type="email" placeholder="Email" name="signUpEmail" autocomplete="off" />
                        <label></label>
                    </div>
                    <div class="infield">
                        <input type="password" placeholder="Password" name="signUpPassword" autocomplete="off" />
                        <label></label>
                    </div>
                    <button type="submit" name="sign-up-btn" class="sign-up">Sign Up</button>
                </form>
            </div>

            <div class="form-container sign-in-container">
                <form action="" method="POST">
                    <h1>Sign in</h1>
                    <div class="social-container">
                        <button type="submit" name="facebook-sign-in-btn" class="social-button">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button type="submit" name="google-sign-in-btn" class="social-button">
                            <a href="">
                                <i class="fab fa-google-plus-g"></i>
                            </a>
                        </button>
                        <button type="submit" name="linkedin-sign-in-btn" class="social-button">
                            <i class="fab fa-linkedin-in"></i>
                        </button>
                    </div>
                    <span>or use your account</span>
                    <div class="infield">
                        <input type="email" placeholder="Email" name="signInEmail" autocomplete="off">
                        <label></label>
                    </div>
                    <div class="infield">
                        <input type="password" placeholder="Password" name="signInPassword">
                        <label></label>
                    </div>
                    <a href="#" class="forgot">Forgot your password?</a>
                    <button type="submit" name="sign-in-btn">Sign In</button>
                </form>
            </div>

            <div class="overlay-container" id="overlayCon">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Welcome Back!</h1>
                        <p>To keep connected with us please login with your personal info</p>
                        <button class="sign-in">Sign In</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Hello, Friend!</h1>
                        <p>Enter your personal details and start journey with us</p>
                        <button class="sign-up">Sign Up</button>
                    </div>
                </div>
                <button id="overlayBtn"></button>
            </div>
        </div>

    </div>

    <!-- js code -->
    <script src="../../../../public/js/index.js"></script>

    <script>
        const formContainer = document.querySelector(".form-contain");
        const Loader = document.querySelector("#loader");
        setTimeout(() => {
            Loader.style.display = 'none';
            formContainer.style.display = 'block';
        }, 6000);
    </script>
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>