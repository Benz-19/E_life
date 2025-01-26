<?php

include_once __DIR__ . '/../../handle_error/handle_error.php';
include_once "database.php";

class User extends Database
{
    protected $userName;
    protected $userEmail;
    protected $userPassword;
    protected $user_is_verified;
    protected $user_verification_code;
    protected $user_google_id;

    static $mail_domain = array("gmail.com", "yahoo.com", "hotmail.com", "outlook.com");


    public function createUserDetail($userName, $userEmail, $userPassword)
    {

        // validate the username
        if (preg_match('/\A\w+\Z/', $userName) !== 1) {
            echo handle_error("Invalid username format. Avoid using special characters, use ONLY letters.");
            return false;
        }

        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPassword = password_hash($userPassword, PASSWORD_DEFAULT);
        $domain = null;

        // Extract the domain part of the email
        $pattern = '/@(.+)$/';
        if (preg_match($pattern, $this->userEmail, $matches)) {
            $domain = $matches[1];
        } else {
            // echo "Invalid email format";
            handle_error("Invalid email format. Use a valid email address");
            return false;
        }

        // Check if the domain is in the allowed list
        if (!in_array($domain, self::$mail_domain)) {
            echo handle_error("Email domain is not allowed. Use a valid email address");
            return false;
        }


        try {
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->Connection()->prepare($sql);

            $stmt->execute([
                ':name' => $this->userName,
                ':email' => $this->userEmail,
                ':password' => $this->userPassword
            ]);
            echo "Insert was successful";
            return true;
        } catch (PDOException $error) {
            echo handle_error("Failed to insert: ") . $error->getMessage();
            return false;
        }
    }


    public function createUserDetailGoogle($userName, $userEmail, $userPassword, $user_is_verified, $user_verification_code, $user_google_id)
    {

        // validate the username
        if (preg_match('/\A\w+\Z/', $userName) !== 1) {
            echo handle_error("Invalid username format. Avoid using special characters.");
            return false;
        }


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
            echo handle_error("Failed to populate the db using the google signup: ") . $error->getMessage();
        }
    }

    // user authentication
    public function authenticateUser($userEmail, $userPassword)
    {
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;

        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':email' => $this->userEmail
            ]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $passwordCheck = password_verify($this->userPassword, $user['password']) || $this->userPassword == $user['password'] ? true : false;
                if ($passwordCheck) {
                    echo "User authenticated";
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

// $test = new User;
// $test->setUserDetailGoogle("Kingsley", "kings@gmail.com", "5555", true, 4449, 7);
