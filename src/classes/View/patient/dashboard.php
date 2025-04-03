<?php
session_start();
if (!isset($_SESSION["patient-login"])) {
    header("Location: ./index.php");
    exit();
}
include_once __DIR__ . "/../../../../vendor/autoload.php";

$patient = new Patient; //Patient
$patientEmail =  $_SESSION["patientEmail"];
$user_id = $patient->getUserID($patientEmail, $_SESSION["user_type"]);
$_SESSION["user_id"] = $user_id;
$username = $patient->getUserDetails($user_id, $_SESSION["user_type"])["name"];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../../public/css/patient.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Document</title>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'roboto', sans-serif;
        }

        #notification-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
            font-size: 24px;
            padding: 0 12px;
            color: black;
        }

        #notification-container::after {
            content: "";
            position: absolute;
            top: 100%;
            bottom: 0;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            white-space: nowrap;
        }

        #notification-container:hover::after {
            content: "notifications";
            font-size: 12px;
            opacity: 1;
        }

        #notification-bell {
            color: #333;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 3px 7px;
            border-radius: 50%;
            display: none;
            /* Initially hidden */
        }

        .username {
            color: brown;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-900 to-blue-900 text-white min-h-screen">
    <header class="fixed w-full bg-white/10 backdrop-blur-lg shadow-lg z-50">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img src="../../../../public/images/medical_logo.png" alt="Logo" class="h-10 mr-3">
                <span class="text-xl font-semibold text-white">E-LIFE</span>
            </div>
            <div class="relative">
                <img src="../../../../public/images/user.png" alt="User" class="h-10 w-10 rounded-full cursor-pointer" id="userMenuToggle">
                <div class="dropdown hidden absolute right-0 mt-2 w-48 bg-white text-gray-900 rounded-lg shadow-lg" id="userDropdown">
                    <a href="#my_details" class="block px-4 py-2 hover:bg-gray-200">My Details</a>
                    <a href="#settings" class="block px-4 py-2 hover:bg-gray-200">Settings</a>
                    <a href="#contact" class="block px-4 py-2 hover:bg-gray-200">Contact Us</a>
                    <a href="logout.php" class="block px-4 py-2 hover:bg-gray-200">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <section class="flex flex-col items-center justify-center h-screen text-center pt-32">
        <h1 class="text-6xl font-extrabold text-white drop-shadow-md">Welcome, <span class="text-blue-400"><?php echo $username; ?></span>!</h1>
        <p class="mt-4 text-xl text-gray-300">Connect with Your Doctor | Manage Your Health</p>
        <a href="#features" class="mt-6 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white text-lg font-semibold rounded-lg shadow-md transition">Get Started</a>
    </section>

    <section id="features" class="container mx-auto py-16 px-6">
        <h2 class="text-4xl font-bold text-center text-white mb-10">Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="bg-white text-gray-900 p-6 rounded-lg shadow-lg hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Secure Chat</h3>
                    <img src="../../../../public/images/send_message.png" alt="Chat" class="h-12">
                </div>
                <p class="mt-2 text-gray-700">Chat securely with your doctor anytime, anywhere.</p>
                <a href="available.doc.php" class="block mt-4 px-4 py-2 bg-blue-500 text-white text-center rounded-lg hover:bg-blue-600 transition">Learn More</a>
            </div>
            <div class="bg-white text-gray-900 p-6 rounded-lg shadow-lg hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Appointment Scheduling</h3>
                    <img src="../../../../public/images/appointment.png" alt="Appointment" class="h-12">
                </div>
                <p class="mt-2 text-gray-700">Easily schedule appointments that fit your schedule.</p>
                <a href="schedule.php" class="block mt-4 px-4 py-2 bg-blue-500 text-white text-center rounded-lg hover:bg-blue-600 transition">Learn More</a>
            </div>
            <div class="bg-white text-gray-900 p-6 rounded-lg shadow-lg hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Prescription Management</h3>
                    <img src="../../../../public/images/prescription.png" alt="Prescription" class="h-12">
                </div>
                <p class="mt-2 text-gray-700">View and manage your prescriptions conveniently.</p>
                <a href="#" class="block mt-4 px-4 py-2 bg-blue-500 text-white text-center rounded-lg hover:bg-blue-600 transition">Learn More</a>
            </div>
        </div>
    </section>
    <footer class="bg-gray-800 py-6 text-center text-gray-400">
        <div class="container mx-auto px-6 py-4 text-white">
            <div class="flex justify-between items-center">
                <div class="text-white-700">&copy; <?php echo date("Y"); ?> E-LIFE. All rights reserved.</div>
                <div>
                    <a href="#contact" class="text-white-600 hover:text-gray-800 px-4">Privacy Policy</a>
                    <a href="#contact" class="text-white-600 hover:text-gray-800 px-4">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
    </div>

    <script>
        // JavaScript for dropdown functionality
        const userMenuToggle = document.getElementById('userMenuToggle');
        const userDropdown = document.getElementById('userDropdown');

        // Toggle dropdown on user image click
        userMenuToggle.addEventListener('click', function(event) {
            userDropdown.classList.toggle('active');
            userDropdown.classList.toggle('hidden');
            event.stopPropagation();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            userDropdown.classList.remove('active');
            userDropdown.classList.add('hidden');
        });

        // Smooth scroll to features section
        const getStartedButton = document.querySelector('a[href="#features"]'); // Get the Get Started button
        getStartedButton.addEventListener('click', (event) => {
            event.preventDefault();
            const featuresSection = document.getElementById('features'); // Get the features section
            featuresSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });

        // Notification handling
        document.addEventListener("DOMContentLoaded", function() {
            function fetchNotifications() {
                let recipientId = <?php echo $_SESSION["user_id"]; ?>;

                fetch(`http://localhost/E_LIFE/src/api/notificationsAPI.php?recipient_id=${recipientId}`)
                    .then(response => response.json())
                    .then(data => {
                        const count = data.notifications ? data.notifications.length : 0;
                        const badge = document.getElementById("notification-count");

                        if (count > 0) {
                            badge.innerText = count;
                            badge.style.display = "inline-block";
                        } else {
                            badge.style.display = "none";
                        }
                    })
                    .catch(error => console.error("Error fetching notifications:", error));
            }

            // Fetch notifications every 10 seconds
            setInterval(fetchNotifications, 10000);
            fetchNotifications();

            // Click event for opening notifications
            document.getElementById("notification-container").addEventListener("click", function() {
                alert("Show notifications dropdown or redirect to notifications page");
            });
        });
    </script>
</body>

</html>