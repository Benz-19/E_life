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
    protected $user_type;

    static $mail_domain = array("gmail.com", "yahoo.com", "hotmail.com", "outlook.com");

    // get user type

    public function getUserType()
    {
        return $this->user_type;
    }
    // set user type

    public function setUserType($user_type)
    {
        $this->user_type = $user_type;
    }

    public function createUserDetail($userName, $userEmail, $userPassword, $user_type)
    {

        // validate the username
        if (preg_match('/\A\w+\Z/', $userName) !== 1) {
            echo handle_error("Invalid username format. Avoid using special characters, use ONLY letters.");
            return false;
        }

        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->user_type = $user_type;
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
            $sql = "INSERT INTO users (name, email, password, user_type) VALUES (:name, :email, :password, :user_type)";
            $stmt = $this->Connection()->prepare($sql);

            $stmt->execute([
                ':name' => $this->userName,
                ':email' => $this->userEmail,
                ':password' => $this->userPassword,
                ':user_type' => $this->user_type,
            ]);
            // echo "Insert was successful";
            return true;
        } catch (PDOException $error) {
            echo handle_error("Failed to insert: ") . $error->getMessage();
            return false;
        }
    }


    public function createUserDetailGoogle($userName, $userEmail, $user_is_verified, $user_verification_code, $user_google_id, $user_type)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->user_is_verified = $user_is_verified;
        $this->user_verification_code = $user_verification_code;
        $this->user_google_id = $user_google_id;
        $this->user_type = $user_type;

        try {
            $sql = "INSERT INTO users (name, email, google_id, is_verified, verification_code, user_type) VALUES (:name, :email, :google_id, :is_verified, :verification_code, :user_type)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':name' => $this->userName,
                ':email' => $this->userEmail,
                ':google_id' => $this->user_google_id,
                ':is_verified' => $this->user_is_verified,
                ':verification_code' => $this->user_verification_code,
                ':user_type' => $this->user_type
            ]);
            // echo "User created successfully";
            return true;
        } catch (PDOException $error) {
            echo handle_error("Failed to create user: " . $error->getMessage());
            return false;
        }
    }

    public function getUserByGoogleId($google_id)
    {
        try {
            $sql = "SELECT * FROM users WHERE google_id = :google_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':google_id' => $google_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo handle_error("Failed to retrieve user: " . $error->getMessage());
            return false;
        }
    }

    public function updateUserDetailGoogle($userId, $name, $email, $google_verified, $google_verification_code)
    {
        try {
            $sql = "UPDATE users SET name = :name, email = :email, is_verified = :is_verified, verification_code = :verification_code WHERE id = :id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':is_verified' => $google_verified,
                ':verification_code' => $google_verification_code,
                ':id' => $userId
            ]);
            // echo "User updated successfully";
            return true;
        } catch (PDOException $error) {
            echo handle_error("Failed to update user: " . $error->getMessage());
            return false;
        }
    }

    public function getLastInsertId()
    {
        return $this->Connection()->lastInsertId();
    }

    // Obtains the user ID from the Database
    public function getUserID($email)
    {
        try {
            $sql = "SELECT user_id FROM users WHERE email = :email";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC)["user_id"];
        } catch (PDOException $error) {
            handle_error("Failed to get the user ID");
            echo $error->getMessage();
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

            // var_dump($user);

            if ($user) {
                $passwordCheck =  $this->userPassword === $user['password'] || password_verify($this->userPassword, $user['password'])  ? true : false;
                if ($passwordCheck) {
                    // echo "User authenticated";
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

    public function verifyUserEmail($username, $email)
    {
        include_once __DIR__ . "../../../mailer/email.mailer.php";
        $this->userName = $username;
        $this->userEmail = $email;
        try {
            if (isset($this->userEmail) && isset($this->userName)) {
                $subject = "Email Verification";
                $verificationCode = rand(10000, 99999);

                $this->user_verification_code = password_hash($verificationCode, PASSWORD_DEFAULT);
                $this->setEmailVerificationCode($this->user_verification_code);

                $body = "Hello {$this->userName}
                <br>We are happy to have received your request to create an account with us.
                <br>Your verification code is: <b>{$verificationCode}</b>
                <br>Kindly use this code to verify your email address.
                <br>Thank you for choosing us.
                <br>Best regards,
                <br>E-LIFE TEAM";

                sendEmail($this->userEmail, $this->userName, $subject, $body);
            }
        } catch (Exception $error) {
            echo handle_error("Failed to send email: ") . $error->getMessage();
        }
    }

    public function setEmailVerificationCode($verificationCode)
    {
        $this->user_verification_code = $verificationCode;
    }

    public function getEmailVerificationCode()
    {
        return $this->user_verification_code;
    }

    // updates the user verification code
    public function updateUserVerificationCode($email, $verificationCode)
    {
        try {
            $sql = "UPDATE users SET verification_code = :is_verified WHERE email = :email";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':is_verified' => $verificationCode,
                ':email' => $email
            ]);
            return true;
        } catch (PDOException $error) {
            echo handle_error("Failed to update user verification code: " . $error->getMessage());
            return false;
        }
    }

    public function updateUserVericationStatus($email, $status)
    {
        try {
            $sql = "UPDATE users SET is_verified = :is_verified WHERE email = :email";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                'is_verified' => $status,
                ':email' => $email
            ]);
            // echo "User verification status updated successfully";
            return true;
        } catch (PDOException $error) {
            echo handle_error("Failed to update user verification status: " . $error->getMessage());
            return false;
        }
    }



    // GET the user TYPE
    public function getUserTypeFromDB($email)
    {
        try {
            $sql = "SELECT user_type FROM users WHERE email=:email";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC)["user_type"];
        } catch (PDOException $error) {
            handle_error("Failed to get user type: " . $error->getMessage());
        }
    }

    // GET the user Details
    public function getUserDetails($user_id)
    {
        try {
            $sql = "SELECT * FROM users WHERE user_id=:user_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            handle_error("Failed to get user details: " . $error->getMessage());
        }
    }

    // Get All Users
    public function getAllUsers()
    {
        try {
            $sql = "SELECT * FROM users";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            handle_error("Failed to get all users: " . $error->getMessage());
        }
    }

    public function getUserEstablishedSchedule($patient_id)
    {
        try {
            $sql = "SELECT * FROM appointment WHERE patient_id = :patient_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':patient_id' => $patient_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            handle_error("Failed to get all users: " . $error->getMessage());
        }
    }
}



// $test = new User;
// // $test->setUserDetailGoogle("Kingsley", "kings@gmail.com", "5555", true, 4449, 7);
// echo "<pre>";
// print_r($test->getUserID("e.life.team.uk@gmail.com"));
// echo "</pre>";
