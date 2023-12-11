<?php
include 'includes/session.php';
include 'candidates_manager.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $candidateManager = new CandidateManager($conn);
    $row = $candidateManager->getCandidateDetails($id);

    echo json_encode($row);
}
?>
