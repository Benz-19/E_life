<?php
require_once __DIR__ . "/../../../vendor/autoload.php";
require "user.model.php";


class Doctor extends User
{

    public function establishSchedule($patientId, $doctor_id, $date, $time, $details, $sender, $receiver)
    {
        try {
            $sql = "INSERT INTO appointment (doctor_id, patient_id, date, time, description, sender, receiver) VALUES (:doctor_id, :patient_id, :date, :time, :details, :sender, :receiver)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':doctor_id' => $doctor_id,
                ':patient_id' => $patientId,
                ':date' => $date,
                ':time' => $time,
                ':details' => $details,
                ':sender' => $sender,
                ':receiver' => $receiver
            ]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getReceivedSchedules($patientId)
    {
        $sql = "SELECT * FROM appointment WHERE patient_id = ?";
        $stmt = $this->Connection()->prepare($sql);
        $stmt->execute([$this->getUserId($patientId)]);
        return $stmt->fetchAll();
    }

    public function getSentSchedules($patientId)
    {
        $sql = "SELECT * FROM appointment WHERE doctor_id = ?";
        $stmt = $this->Connection()->prepare($sql);
        $stmt->execute([$this->getUserId($patientId)]);
        return $stmt->fetchAll();
    }
}
