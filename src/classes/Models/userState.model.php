<?php
include_once __DIR__ . "/../../handle_error/handle_error.php";
include_once "database.php";
include "user.model.php";


class loggedInUser extends User
{
    public function setLoggedInUser($userID, $userName, $user_type)
    {
        try {
            $sql = "INSERT INTO logged_in_users (user_name, user_id, user_type) VALUES (:username, :id, :userType)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':username' => $userName,
                ':id' => $userID,
                ':userType' => $user_type
            ]);
            return true;
        } catch (PDOException $error) {
            echo handle_error("Unable to set the logged-in user details...") . "<br>" . $error->getMessage();
        }
    }

    public function getLoggedInUsers()
    {
        try {
            $sql = "SELECT * FROM logged_in_users";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo handle_error("Unable to GET the logged-in user details...") . "<br>" . $error->getMessage();
        }
    }

    public function removeLoggedInUser($userID)
    {
        try {
            $sql = "DELETE FROM logged_in_users WHERE user_id = :id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":id" => $userID
            ]);
        } catch (PDOException $error) {
            echo handle_error("Unable to DELETE the logged-in user details...") . "<br>" . $error->getMessage();
        }
    }
}
