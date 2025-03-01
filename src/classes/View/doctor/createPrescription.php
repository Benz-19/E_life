<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$patient = new User();
$patient_id = intval($_GET['id']);
$patient_info = $patient->getUserDetails($patient_id);
// print_r($patient_info);
if (!$patient_info || $patient_info['user_type'] !== 'patient') {
    die("Patient not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $description = trim($_POST['description']);
    $reason = trim($_POST['reason']);
    $hypothesis = trim($_POST['hypothesis']);
    $doctor_id = $_SESSION['user_id']; // Assuming doctor is logged in

    if (empty($description) || empty($reason) || empty($hypothesis)) {
        $error = "All fields are required.";
    } else {
        $prescription = new Prescription();
        $prescription_id = $prescription->addPrescription($patient_id, $doctor_id, $description, $reason, $hypothesis);
        if ($prescription_id) {
            $success = "Prescription generated successfully.";
        } else {
            $error = "Failed to generate prescription.";
        }
    }
}

if (isset($_POST['previewBtn'])) {
    if (generatePrescription($patient_info['name'], $description, $reason, $hypothesis, date($date))) {
        include_once __DIR__ . '/../../../includes/genratePrescription.php';
        echo '
        <img src="../../../includes/genratePrescription.php" alt="Prescription">
        ';
    } else {
        echo "unable to generate prescription!!!";
    }
} else {
    echo "Failed to generate preview";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Prescription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <!-- Return to Appointments Page -->
    <div>
        <div id="appointmentList" class="space-y-3">
            <div class="p-4 bg-gray-200 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Return to Appointments</h3>
                <button type="submit" name="schedule" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                    <a href="prescription.php">return</a>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-center mb-4">Create Prescription for <span class="text-red-500"><?= htmlspecialchars(strtoupper($patient_info['name'])) ?></span></h2>

        <?php if (isset($success)): ?>
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block font-semibold">Description</label>
                <textarea name="description" class="w-full p-2 border rounded-lg" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block font-semibold">Reason</label>
                <textarea name="reason" class="w-full p-2 border rounded-lg" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block font-semibold">Hypothesis</label>
                <textarea name="hypothesis" class="w-full p-2 border rounded-lg" required></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                Generate Prescription
            </button>
        </form>

        <form action="" method="post">
            <?php if (isset($success)): ?>
                <div class="bg-gray-200 p-4 mt-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Prescription Preview</h3>
                    <p><strong>Patient:</strong> <?= htmlspecialchars($patient_info['name']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($description) ?></p>
                    <p><strong>Reason:</strong> <?= htmlspecialchars($reason) ?></p>
                    <p><strong>Hypothesis:</strong> <?= htmlspecialchars($hypothesis) ?></p>

                    <button class="bg-green-500 text-white px-4 py-2 rounded-lg mt-2" type="submit" name="previewBtn">Preview Prescription</button>

                    <button class="bg-green-500 text-white px-4 py-2 rounded-lg mt-2">
                        Send to <?= htmlspecialchars($patient_info['name']) ?>
                    </button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>