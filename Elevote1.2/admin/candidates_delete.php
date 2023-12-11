<?php
include 'includes/session.php';  // Make sure to include the database connection
include 'candidates_manager.php';

if (isset($_POST['delete'])) {
    $candidateManager = new CandidateManager($conn);

    $id = $_POST['id'];

    if ($candidateManager->deleteCandidate($id)) {
        $_SESSION['success'] = 'Candidate deleted successfully';
    } else {
        $_SESSION['error'] = 'Error deleting candidate: ' . $candidateManager->getError();
    }
    var_dump($_SESSION); // Debugging line
} else {
    $_SESSION['error'] = 'Select item to delete first';
}
// Redirect back to candidates.php
header('location: candidates.php');
exit();
?>
