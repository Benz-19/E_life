<?php

header("Content-Type: application/json");
include_once __DIR__ . "/../classes/Models/chat.model.php";

$chatModel = new ChatModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $doctor_id = $_GET['doctor_id'] ?? null;
    $patient_id = $_GET['patient_id'] ?? null;

    if (!$doctor_id || !$patient_id) {
        echo json_encode(["error" => "Missing doctor_id or patient_id"]);
        exit;
    }

    $messages = $chatModel->getMessages($doctor_id, $patient_id);
    echo json_encode(["messages" => $messages]);
    exit;
}

echo json_encode(["error" => "Invalid request"]);
exit;
