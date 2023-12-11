<?php

class VotersManager{
    private $conn;
    private $error;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->error = null;
    }

    public function addVoter($firstname, $lastname, $password, $photo)
    {
        // Generate voters ID
        $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $voter = substr(str_shuffle($set), 0, 15);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO voters (voters_id, password, firstname, lastname, photo) VALUES (:voter, :password, :firstname, :lastname, :photo)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':voter', $voter, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Voter added successfully';
        } else {
            $_SESSION['error'] = $stmt->errorInfo()[2]; // Fetch the specific error message
        }

        header('location: voters.php');
        exit();
    }

    public function deleteVoter($id)
    {
        $sql = "DELETE FROM voters WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try{
            if($stmt->execute()) {
        return true;
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }
        } catch (PDOException $e) {
            $this->setError($e->getMessage());
                return false;
            }
        }

    public function editVoter($id, $firstname, $lastname, $password, $photo)
    {
        $sql = "SELECT * FROM voters WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($password == $row['password']) {
            $password = $row['password'];
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        $updateSql = "UPDATE voters SET firstname = :firstname, lastname = :lastname, password = :password WHERE id = :id";
        $updateStmt = $this->conn->prepare($updateSql);
        $updateStmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $updateStmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $updateStmt->bindParam(':password', $password, PDO::PARAM_STR);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            $_SESSION['success'] = 'Voter updated successfully';
        } else {
            $_SESSION['error'] = $updateStmt->errorInfo()[2]; // Fetch the specific error message
        }

        header('location: voters.php');
        exit();
    }

    public function updateVoterPhoto($id, $filename)
    {
        $sql = "UPDATE voters SET photo = :filename WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Photo updated successfully';
        } else {
            $_SESSION['error'] = $stmt->errorInfo()[2];
        }

        header('location: voters.php');
        exit();
    }

    public function getVoterDetails($id) {
        // Add your validation and error handling here
        $sql = "SELECT * FROM voters WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        // Execute the statement and fetch the details
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $this->setError($stmt->errorInfo()[2]);
            return false;
        }
    }
    
    
    
    

    public function getError()
{
    return $this->error;
}

    private function setError($errorMessage)
{
    $this->error = $errorMessage;
    }
}
?>
