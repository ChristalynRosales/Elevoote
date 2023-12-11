<?php

class BallotManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function movePositionUp($id)
    {
        $output = array('error' => false);

        $row = $this->getPositionDetails($id);

        $priority = $row['priority'] - 1;

        if ($priority == 0) {
            $output['error'] = true;
            $output['message'] = 'This position is already at the top';
        } else {
            $this->conn->query("UPDATE positions SET priority = priority + 1 WHERE priority = '$priority'");
            $this->conn->query("UPDATE positions SET priority = '$priority' WHERE id = '$id'");
        }

        return json_encode($output);
    }

    public function movePositionDown($id)
    {
        $output = array('error' => false);

        $pquery = $this->conn->query("SELECT * FROM positions");
        $row = $this->getPositionDetails($id);

        $priority = $row['priority'] + 1;

        if ($priority > $pquery->num_rows) {
            $output['error'] = true;
            $output['message'] = 'This position is already at the bottom';
        } else {
            $this->conn->query("UPDATE positions SET priority = priority - 1 WHERE priority = '$priority'");
            $this->conn->query("UPDATE positions SET priority = '$priority' WHERE id = '$id'");
        }

        return json_encode($output);
    }

    public function fetchBallot()
{
    $output = array();

    try {
        $query = $this->conn->query("SELECT * FROM positions ORDER BY priority ASC");

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $positionId = $row['id'];
            $candidates = $this->fetchCandidates($positionId);

            $output[] = array(
                'id' => $positionId,
                'description' => $row['description'],
                'candidates' => $candidates,
                'max_vote' => $row['max_vote'],
                'priority' => $row['priority'],
            );
        }

        return json_encode($output);
    } catch (PDOException $e) {
        // Handle the exception (e.g., log the error)
        echo "Error: " . $e->getMessage();
        return json_encode(array('error' => true, 'message' => 'Error fetching ballot.'));
    }
}

    private function fetchCandidates($positionId)
{
    $candidates = array();

    $sql = "SELECT * FROM candidates WHERE position_id = :positionId";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':positionId', $positionId, PDO::PARAM_INT);
    $stmt->execute();

    while ($crow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $image = (!empty($crow['photo'])) ? '../images/'.$crow['photo'] : '../images/profile.jpg';

        $candidates[] = array(
            'firstname' => $crow['firstname'],
            'lastname' => $crow['lastname'],
            'photo' => $image,
        );
    }

    return $candidates;
}

    private function getPositionDetails($id)
{
    $sql = "SELECT * FROM positions WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
$ballotManager = new BallotManager($conn);
?>
