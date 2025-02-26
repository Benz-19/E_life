<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Schedule Appointments</h2>

        <!-- Appointment Form -->
        <form id="scheduleForm" class="mb-6">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium" for="date">Select Date</label>
                <input type="date" id="date" name="date" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium" for="time">Select Time</label>
                <input type="time" id="time" name="time" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium" for="details">Appointment Details</label>
                <textarea id="details" name="details" class="w-full p-2 border rounded-lg" rows="3"></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Schedule</button>
        </form>

        <!-- Scheduled Appointments -->
        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Upcoming Appointments</h3>
            <ul id="appointmentList" class="space-y-3">
                <li class="p-4 bg-gray-200 rounded-lg">No appointments scheduled yet.</li>
            </ul>
        </div>
    </div>
</body>

</html>