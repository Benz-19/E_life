<?php
include_once "../Models/database.php";

$db = new Database;

if (isset($db)) {
    echo "yes";
} else {
    echo "no";
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/test.css">
    <title>Document</title>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>
    <div class="flex flex-col h-screen bg-gradient-to-r from-blue-600 to-gray-600">
        <header class="fixed inset-x-0 top-0 z-50 bg-white/90 shadow backdrop-blur-md">
            <div class="container mx-auto px-6 py-3 flex items-center">
                <div class="container mx-auto px-6 py-3 flex justify-between items-center">
                    <a href="#" class="container mx-auto px-6 py-3 flex items-center">
                        <img src="../../../public/images/medical_logo.png" alt="Health Logo" class="h-10 mr-3">
                        <div class="text-xl font-semibold text-gray-800">E-LIFE</div>
                    </a>
                    <div class="relative">
                        <img src="../../../public/images/user.png" alt="User" class="h-10 w-10 rounded-full cursor-pointer" id="userMenuToggle">
                        <div class="dropdown hidden absolute right-0 z-20 mt-2 w-48 bg-white rounded-lg shadow-lg" id="userDropdown">
                            <a href="#my_details" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 mx-card">My Details</a>
                            <a href="#settings" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 mx-card">Settings</a>
                            <a href="#contact" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 mx-card">Contact Us</a>
                            <a href="#logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 mx-card">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section class="flex flex-col items-center justify-center flex-1 text-center pt-80 pb-48">
            <h1 class="text-5xl font-bold text-white">Connect with Your Doctor</h1>
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
                        <img src="../../../public/images/send_message.png" alt="Health Logo" class="h-10 mr-3">
                    </div>
                    <p class="mt-2 text-gray-600">Chat securely with your doctor anytime, anywhere.</p>
                    <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300 mx-card">Learn More</button>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform my-card">
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-lg transition-transform transform">
                        <h3 class="text-xl font-semibold">Appointment Scheduling</h3>
                        <img src="../../../public/images/appointment.png" alt="Health Logo" class="h-10 mr-3">
                    </div>
                    <p class="mt-2 text-gray-600">Easily schedule appointments that fit your schedule.</p>
                    <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300 mx-card">Learn More</button>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform my-card">
                    <div class="flex justify-between bg-white p-6 rounded-lg shadow-lg transition-transform transform">
                        <h3 class="text-xl font-semibold">Prescription Management</h3>
                        <img src="../../../public/images/prescription.png" alt="Health Logo" class="h-10 mr-3">
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
            userDropdown.classList.toggle('active'); // Toggle the dropdown visibility
            userDropdown.classList.toggle('hidden'); // Toggle hidden class
            event.stopPropagation(); // Prevent click from bubbling up to the document
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            userDropdown.classList.remove('active'); // Hide dropdown
            userDropdown.classList.add('hidden'); // Add hidden class to dropdown
        });

        // Smooth scroll to features section
        const getStartedButton = document.querySelector('a[href="#features"]'); // Get the Get Started button
        getStartedButton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default anchor click behavior
            const featuresSection = document.getElementById('features'); // Get the features section
            featuresSection.scrollIntoView({
                behavior: 'smooth', // Smooth scroll
                block: 'start' // Scroll to the top of the section
            });
        });
    </script>
</body>

</html>