<?php
require_once __DIR__ . "/../../../vendor/autoload.php";
require "user.model.php";

class Patient extends User
{
    public function authenticatePatient($userEmail, $userPassword)
    {
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;


        try {
            $sql = "SELECT * FROM patient WHERE email = :email";
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
}
