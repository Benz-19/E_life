<?php
include_once "database.php";
include "user.model.php";


class loggedInUser extends User
{
    public function setLoggedInUser($userID, $userName, $user_type, $userPresence)
    {
        try {
            $sql = "INSERT INTO logged_in_users (user_name, user_id, user_type, presence) VALUES (:username, :id, :userType, :presence)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':username' => $userName,
                ':id' => $userID,
                ':userType' => $user_type,
                ':presence' => $userPresence
            ]);
            return true;
        } catch (PDOException $error) {
            echo "Unable to set the logged-in user details..." . "<br>" . $error->getMessage();
        }
    }

    public function getLoggedInUsers()
    {
        try {
            $sql = "SELECT * FROM logged_in_users";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo "Unable to GET the logged-in user details..." . "<br>" . $error->getMessage();
        }
    }

    public function updateLoggedInUserState($newPresence, $id)
    {
        try {
            $sql = "UPDATE logged_in_users SET presence = :presence WHERE user_id = :id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":presence" => $newPresence,
                ":id" => $id
            ]);
        } catch (PDOException $error) {
            echo "Unable to Update the logged-in user details..." . "<br>" . $error->getMessage();
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
            echo "Unable to DELETE the logged-in user details..." . "<br>" . $error->getMessage();
        }
    }
}
