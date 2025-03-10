<?php
session_start();
if (!isset($_SESSION["patient-login"])) {
    header("Location: ./index.php");
}
include_once __DIR__ . "/../../../../vendor/autoload.php";

$patient = new Patient; //Patient
$patientEmail =  $_SESSION["patientEmail"];
$user_id = $patient->getUserID($patientEmail, $_SESSION["user_type"]);
$_SESSION["user_id"] = $user_id;
// echo "ID = {$_SESSION["user_id"]}";
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
    <title>Document</title>
    <style>
        html {
            scroll-behavior: smooth;
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

<body>
    <div class="flex flex-col h-screen bg-gradient-to-r from-blue-600 to-gray-600">
        <header class="fixed inset-x-0 top-0 z-50 bg-white/90 shadow backdrop-blur-md">
            <div class="container mx-auto px-6 py-3 flex items-center">
                <div class="container mx-auto px-6 py-3 flex justify-between items-center">
                    <a href="#" class="container mx-auto px-6 py-3 flex items-center">
                        <img src="../../../../public/images/medical_logo.png" alt="Health Logo" class="h-10 mr-3">
                        <div class="text-xl font-semibold text-gray-800">E-LIFE</div>
                    </a>
                    <div class="relative">
                        <img src="../../../../public/images/user.png" alt="User" class="h-10 w-10 rounded-full cursor-pointer" id="userMenuToggle">
                        <div class="dropdown hidden absolute right-0 z-20 mt-2 w-48 bg-white rounded-lg shadow-lg" id="userDropdown">
                            <a href="#my_details" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 mx-card">My Details</a>
                            <a href="#settings" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 mx-card">Settings</a>
                            <a href="#contact" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 mx-card">Contact Us</a>
                            <a href="#logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 mx-card">Logout</a>
                        </div>
                    </div>
                </div>

                <!-- Notification -->
                <div id="notification-container">
                    <i class="fa fa-bell" id="notification-bell"></i>
                    <span id="notification-count" class="badge">0</span>
                </div>
            </div>
        </header>
        <section class="flex flex-col items-center justify-center flex-1 text-center pt-80 pb-48">
            <h1 class="text-5xl font-bold text-white">Welcome <span class="username"><?php echo $username; ?></span>!</h1>
            <h5 class="text-3xl font-bold text-white">Connect with Your Doctor</h5>
            <p class="mt-4 text-lg text-white">Chat, Schedule, and Manage Your Health Effortlessly.</p>

            <div class="relative w-full mt-6">
                <a href="#features" class="mt-6 inline-block bg-blue-600 text-white font-semibold rounded-lg px-6 py-3 hover:bg-blue-500 transition duration-300 mx-card bounce">Get Started</a>
            </div>

        </section>
        <section id="features" class="container mx-auto pt-24 pb-40">
            <h2 class="text-3xl font-bold text-center text-black underline">Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-6">
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform my-card">
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-lg transition-transform transform">
                        <h3 class="text-xl font-semibold">Secure Chat</h3>
                        <img src="../../../../public/images/send_message.png" alt="Send Logo" class="h-10 mr-3">
                    </div>
                    <p class="mt-2 text-gray-600">Chat securely with your doctor anytime, anywhere.</p>
                    <a href="available.doc.php">
                        <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300 mx-card">Learn More</button>
                    </a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform my-card">
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-lg transition-transform transform">
                        <h3 class="text-xl font-semibold">Appointment Scheduling</h3>
                        <img src="../../../../public/images/appointment.png" alt="Schedule Logo" class="h-10 mr-3">
                    </div>
                    <p class="mt-2 text-gray-600">Easily schedule appointments that fit your schedule.</p>
                    <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300 mx-card">
                        <a href="<?php echo 'schedule.php'; ?>">Learn More</a>
                    </button>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform my-card">
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-lg transition-transform transform">
                        <h3 class="text-xl font-semibold">Prescription Management</h3>
                        <img src="../../../../public/images/prescription.png" alt="Presciption Logo" class="h-10 mr-3">
                    </div>
                    <p class="mt-2 text-gray-600">View and manage your prescriptions conveniently.</p>
                    <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300 mx-card">Learn More</button>
                </div>
            </div>
        </section>
        <footer class="bg-white">
            <div class="container mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-gray-700">&copy; <?php echo date("Y"); ?> E-LIFE. All rights reserved.</div>
                    <div>
                        <a href="#contact" class="text-gray-600 hover:text-gray-800 px-4">Privacy Policy</a>
                        <a href="#contact" class="text-gray-600 hover:text-gray-800 px-4">Terms of Service</a>
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