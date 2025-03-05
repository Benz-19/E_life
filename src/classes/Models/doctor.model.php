<?php
require_once __DIR__ . "/../../../vendor/autoload.php";
require "user.model.php";


class Doctor extends User
{

    public function authenticateDoctor($userEmail, $userPassword)
    {
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;


        try {
            $sql = "SELECT * FROM doctor WHERE email = :email";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':email' => $this->userEmail
            ]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($user) {
                $passwordCheck =  $this->userPassword === $user['password'] || password_verify($this->userPassword, $user['password'])  ? true : false;
                if ($passwordCheck) {
                    return true;
                } else {
                    echo handle_error("Password is incorrect");
                    return false;
                }
            } else {
                echo handle_error("User not found");
                return false;
            }
        } catch (PDOException $error) {
            echo handle_error("Failed to authenticate user: ") . $error->getMessage();
        }
    }


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


    public function getUserId($id)
    {
        $sql = "SELECT user_id FROM users WHERE user_id = :id";
        $stmt = $this->Connection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['user_id'] : null; // Return ID if found, otherwise null
    }


    public function getReceivedSchedules($userId)
    {
        $sql = "SELECT * FROM appointment WHERE doctor_id = ?";
        $stmt = $this->Connection()->prepare($sql);
        $stmt->execute([$this->getUserId($userId)]);
        return $stmt->fetchAll();
    }

    public function getSentSchedules($Id)
    {
        $sql = "SELECT * FROM appointment WHERE doctor_id = ?";
        $stmt = $this->Connection()->prepare($sql);
        $stmt->execute([$this->getUserId($Id)]);
        return $stmt->fetchAll();
    }
}
