<?php
include 'includes/session.php';
include_once 'includes/connection.php';
include 'voters_manager.php';

$votersManager = new VotersManager($conn);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $row = $votersManager->getVoterDetails($id);
    echo json_encode($row);
} else {
    // Handle the case when the ID is not provided
    echo json_encode(['error' => 'ID not provided']);
}

?>

