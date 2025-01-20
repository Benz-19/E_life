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
    <!-- Bootstrap -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
    <!-- css stylesheet -->
    <link rel="stylesheet" href="public/css/index.css">
    <style>
        .container-main {
            position: relative;
            width: 850px;
            height: 500px;
            background-color: white;
            box-shadow: 25px 30px 55px #5557;
            border-radius: 13px;
            overflow: hidden;
        }

        @media only screen and (max-width: 1000px) {
            .container-main {
                width: 610px;
            }

            h1 {
                text-align: center;
            }

            .overlay {
                display: flex;
                justify-content: space-between;
                text-align: center;
            }

            .overlay-right {
                padding: 0;
            }

            .overlay-right h1 {
                width: 100%;
                margin-inline-start: 100px;
            }

            .overlay-right p {
                margin-inline-start: 100px;
            }

            .overlay-container .overlay .overlay-panel button {
                margin-inline-start: 100px;
            }

            /* overlay left */
            .overlay-left {
                padding: 0;
            }

            .overlay-left h1 {
                width: 100%;
                margin-inline-start: 100px;
                margin-inline-end: 50px;
                font-size: 17px;
            }

            .overlay-left p {
                margin-inline-start: 70px;
                margin-inline-end: 35px;
            }



            #overlayCon .overlay .overlay-left button {
                margin-inline-start: 140px;
                margin-inline-end: 100px;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <div class="container-main" id="container-main">
        <div class="form-container sign-up-container">
            <form action="#">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <div class="infield">
                    <input type="text" placeholder="Full Name" />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="email" placeholder="Email" name="email" autocomplete="off" />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" />
                    <label></label>
                </div>
                <button>Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="#">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your account</span>
                <div class="infield">
                    <input type="email" placeholder="Email" name="email" autocomplete="off" />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" />
                    <label></label>
                </div>
                <a href="#" class="forgot">Forgot your password?</a>
                <button>Sign In</button>
            </form>
        </div>
        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button>Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button>Sign Up</button>
                </div>
            </div>
            <button id="overlayBtn"></button>
        </div>
    </div>



    <!-- js code -->
    <script src="public/js/index.js"></script>
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>