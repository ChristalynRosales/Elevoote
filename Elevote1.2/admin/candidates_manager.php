<?php

class CandidateManager {
    private $conn;
    private $error;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->error = null;
    }
    public function getCandidateDetails($id)
    {
        $candidateDetails = array();

        // Modify the SQL query based on your database schema
        $sql = "SELECT *, candidates.id AS canid FROM candidates LEFT JOIN positions ON positions.id=candidates.position_id WHERE candidates.id = :id ORDER BY positions.priority ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            try {
                $stmt->execute();
                $candidateDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->setError($e->getMessage());
            }
    
            return $candidateDetails;
        }
    
        //$row = $stmt->fetch(PDO::FETCH_ASSOC);

        //return $row;

    public function getError() {
        return $this->error;
    }

    public function addCandidate($firstname, $lastname, $position, $platform, $filename) {
        // Add your validation and error handling here
        $sql = "INSERT INTO candidates (position_id, firstname, lastname, photo, platform) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute([$position, $firstname, $lastname, $filename, $platform])) {
            return true;
        } else {
            $this->error = $stmt->errorInfo();
            return false;
        }
    }

    public function editCandidate($id, $firstname, $lastname, $position, $platform){
    // Add your validation and error handling here
        $sql = "UPDATE candidates SET position_id = ?, firstname = ?, lastname = ?, platform = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

    try {
        if ($stmt->execute([$position, $firstname, $lastname, $platform, $id])) {
            return true;
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    } catch (PDOException $e) {
        $this->setError($e->getMessage());
        return false;
    }
}


    public function updatePhoto($id, $filename) {
        // Add your validation and error handling here
        $sql = "UPDATE candidates SET photo = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([$filename, $id]);
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function deleteCandidate($id)
    {
        $sql = "DELETE FROM candidates WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new PDOException($stmt->errorInfo()[2]);
            }
        } catch (PDOException $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    public function getCandidatesList() {
        // Add your validation and error handling here
        $sql = "SELECT *, candidates.id AS canid FROM candidates LEFT JOIN positions ON positions.id=candidates.position_id ORDER BY positions.priority ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    private function setError($errorMessage){
        $this->error = $errorMessage;
    }
}

?>
