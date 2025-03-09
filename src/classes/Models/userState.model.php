<?php
include_once "database.php";
include_once "user.model.php";


class loggedInUser extends User
{
    public function userExists($userID)
    {
        try {
            $sql = "SELECT user_id FROM logged_in_users WHERE user_id = :id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([":id" => $userID]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch result

            return $row !== false; // Return true if user exists
        } catch (PDOException $error) {
            echo "Error checking user existence: " . $error->getMessage();
            return false;
        }
    }

    public function setLoggedInUser($userID, $userName, $user_type, $userPresence)
    {
        try {
            if ($this->userExists($userID)) {
                // User exists, update their details
                $sql = "UPDATE logged_in_users 
                        SET user_name = :userName, 
                            user_type = :userType, 
                            presence = :presence 
                        WHERE user_id = :id";
            } else {
                // User does not exist, insert new record
                $sql = "INSERT INTO logged_in_users (user_id, user_name, user_type, presence) 
                        VALUES (:id, :userName, :userType, :presence)";
            }

            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":id" => $userID,
                ":userName" => $userName,
                ":userType" => $user_type,
                ":presence" => $userPresence
            ]);

            return true;
        } catch (PDOException $error) {
            echo "Unable to set the logged-in user details...<br>" . $error->getMessage();
            return false;
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
