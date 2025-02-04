<?php
include_once __DIR__ . "/../../handle_error/handle_error.php";
include "user.model.php";

class loggedInUser extends User
{
    public function setLoggedInUser($userID, $userName, $user_type)
    {
        try {
            $sql = "INSERT INTO logged_in_users VALUES (:id, :username, :userType)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                'user_name' => $userName,
                'user_id' => $userID,
                'user_type' => $user_type
            ]);
            return true;
        } catch (PDOException $error) {
            echo handle_error("Unable to set the logged-in user details...") . "<br>" . $error->getMessage();
        }
    }

    public function getLoggedInUsers()
    {
        $sql = "SELECT * FROM logged_in_users";
        $stmt = $this->Connection()->prepare($sql);
        $stmt->execute();
    }
}
