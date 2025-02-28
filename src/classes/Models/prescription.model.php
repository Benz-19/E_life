<?php
require_once __DIR__ . "/../../../vendor/autoload.php";

class Prescription extends Database
{

    public function addPrescription($patient_id, $doctor_id, $description, $reason, $hypothesis)
    {
        $sql = "INSERT INTO prescriptions (patient_id, doctor_id, description, reason, hypothesis) 
                VALUES (?, ?, ?, ?, ?)";
        return $this->insert($sql, [$patient_id, $doctor_id, $description, $reason, $hypothesis]);
    }

    public function getPrescriptionsByPatientId($patient_id)
    {
        $sql = "SELECT p.*, u.name as doctor_name 
                FROM prescriptions p 
                JOIN users u ON p.doctor_id = u.id 
                WHERE p.patient_id = ? 
                ORDER BY p.created_at DESC";
        return $this->fetchAll($sql, [$patient_id]);
    }
}
