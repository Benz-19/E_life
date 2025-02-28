<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

$user = new Doctor();
$patients = $user->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/YOUR_FONTAWESOME_KIT.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="p-4 bg-gray-200 rounded-lg flex justify-start mx-4">
        <button class="bg-red-500 text-white px-4 py-2 rounded-lg">
            <a href="dashboard.php">Return To Dashboard</a>
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-center mb-4">Select a Patient for Prescription</h2>

        <div class="mb-4 relative">
            <input type="text" id="searchPatient" class="w-full px-4 py-2 border rounded-lg" placeholder="Search patient by email...">
            <button class="absolute right-3 top-2 text-gray-600">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <table class="w-full bg-white border rounded-lg shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">ID</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody id="patientList">
                <?php foreach ($patients as $patient): ?>
                    <?php if ($patient['user_type'] === 'patient'): ?>
                        <tr class="border-t">
                            <td class="p-2"> <?= htmlspecialchars($patient['user_id']) ?> </td>
                            <td class="p-2"> <?= htmlspecialchars($patient['name']) ?> </td>
                            <td class="p-2"> <?= htmlspecialchars($patient['email']) ?> </td>
                            <td>
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                                    <a href="createPrescription.php?id=<?php echo $patient['user_id']; ?>">Create Prescription</a>
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#searchPatient').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('#patientList tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>

</html>