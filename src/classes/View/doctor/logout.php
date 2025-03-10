<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["doctor-login"])) {
    header("Location: ./index.doctor.php");
    exit();
}

echo '
<script>
   setTimeout(()=>{
    window.location.href = "processLogout.php";
    }, 7000);
</script>';
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
        <h1 class="text-lg mt-3">Logging Out</h1>
        <div class="loader-dots">
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
            <span class="dot animate-pulse bg-blue-600 rounded-full h-2 w-2 mx-1"></span>
        </div>
    </div>

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