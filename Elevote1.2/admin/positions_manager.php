<?php

class PositionManager
{
    private $conn;
    private $error;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->error = null;
        
    }

    public function getAllPositions()
    {
        $sql = "SELECT * FROM positions ORDER BY priority ASC";
        $query = $this->conn->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPositionById($id)
    {
        $sql = "SELECT * FROM positions WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePosition($id, $description, $maxVote)
    {
        $sql = "UPDATE positions SET description = :description, max_vote = :max_vote WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':max_vote', $maxVote, PDO::PARAM_INT);
    
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
    

    public function deletePosition($id)
    {
        $sql = "DELETE FROM positions WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }


    public function addPosition($description, $maxVote)
    {
        // Use COALESCE to handle the case when the table is empty
        $sql = "SELECT COALESCE(MAX(priority), 0) as max_priority FROM positions";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $priority = $row['max_priority'] + 1;
    
        $sql = "INSERT INTO positions (description, max_vote, priority) VALUES (:description, :max_vote, :priority)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':max_vote', $maxVote, PDO::PARAM_INT);
        $stmt->bindParam(':priority', $priority, PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Handle the exception (e.g., log the error or return false)
            $this->setError($e->getMessage()); // Set the error message
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
