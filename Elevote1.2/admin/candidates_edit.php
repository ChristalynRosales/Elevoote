<?php
include 'includes/session.php';
 // Make sure to include the database connection
include 'candidates_manager.php';

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $newFirstname = $_POST['firstname'];
    $newLastname = $_POST['lastname'];
    $newPosition = $_POST['position'];
    $newPlatform = $_POST['platform'];

    $candidateManager = new CandidateManager($conn);
    $candidateDetails = $candidateManager->getCandidateDetails($id);

    if ($candidateDetails) {
        // Extract existing candidate details
        $existingFirstname = $candidateDetails['firstname'];
        $existingLastname = $candidateDetails['lastname'];
        $existingPosition = $candidateDetails['position_id'];
        $existingPlatform = $candidateDetails['platform'];

        // Check if the details have changed before updating
        if ($existingFirstname !== $newFirstname || $existingLastname !== $newLastname || $existingPosition !== $newPosition || $existingPlatform !== $newPlatform) {
            if ($candidateManager->editCandidate($id, $newFirstname, $newLastname, $newPosition, $newPlatform)) {
                $_SESSION['success'] = 'Candidate updated successfully';
            } else {
                $_SESSION['error'] = 'Error updating Candidate: ' . $candidateManager->getError();
            }
        } else {
            $_SESSION['info'] = 'No changes made to the candidate details.';
        }
    } else {
        $_SESSION['error'] = 'Error fetching candidate details';
    }
} else {
    $_SESSION['error'] = 'Invalid request';
}

// Redirect back to candidates.php
header('location: candidates.php');
exit();
?>
