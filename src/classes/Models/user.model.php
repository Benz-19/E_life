<?php

include_once "database.php";


class User extends Database
{
    private $userName;
    private $userEmail;
    private $userPassword;
    private $user_is_verified;
    private $user_verification_code;
    private $user_google_id;


    public function setUserDetail($userName, $userEmail, $userPassword)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPassword = password_hash($userPassword, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':name' => $this->userName,
                ':email' => $this->userEmail,
                ':password' => $this->userPassword
            ]);
            echo "Insert was successful";
        } catch (PDOException $error) {
            echo "Failed to insert: " . $error->getMessage();
        }
    }
    public function setUserDetailGoogle($userName, $userEmail, $userPassword, $user_is_verified, $user_verification_code, $user_google_id)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
        $this->user_google_id = $user_google_id;
        $this->user_is_verified = $user_is_verified;
        $this->user_verification_code = $user_verification_code;

        try {
            $sql = "INSERT INTO users (name, email, password, is_verified, verification_code, google_id) VALUES (:name, :email, :password,:is_verified,:verification_code, :google_id)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':name' => $this->userName,
                ':email' => $this->userEmail,
                ':password' => $this->userPassword,
                ':is_verified' => $this->user_is_verified,
                ':verification_code' => $this->user_verification_code,
                ':google_id' => $this->user_google_id
            ]);
            echo "Successfully populated the db using the google signup";
        } catch (PDOException $error) {
            echo "Failed to populate the db using the google signup: " . $error->getMessage();
        }
    }
}

// $test = new User;
// $test->setUserDetailGoogle("Kingsley", "kings@gmail.com", "5555", true, 4449, 7);
