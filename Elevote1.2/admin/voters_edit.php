<?php
include 'includes/session.php';
include 'includes/header.php';
include_once 'includes/connection.php';
include 'voters_manager.php';

$votersManager = new VotersManager($conn);

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $newFirstname = $_POST['firstname'];
    $newLastname = $_POST['lastname'];
    $newPassword = $_POST['password'];
    $newPhoto = $_FILES['photo']['name'];

    // Check if a new photo is uploaded
    if (!empty($newPhoto)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $newPhoto);
    }

    $voterDetails = $votersManager->getVoterDetails($id);


    if ($voterDetails) {
        // Extract existing voter details
        $existingFirstname = $voterDetails['firstname'];
        $existingLastname = $voterDetails['lastname'];
        $existingPassword = $voterDetails['password'];
        $existingPhoto = $voterDetails['photo'];

        // Check if the details have changed before updating
        if ($existingFirstname !== $newFirstname || $existingLastname !== $newLastname || $existingPassword !== $newPassword || $existingPhoto !== $newPhoto) {
            if ($votersManager->editVoter($id, $newFirstname, $newLastname, $newPassword, $newPhoto)) {
                $_SESSION['success'] = 'Voter updated successfully';
            } else {
                $_SESSION['error'] = 'Error updating Voter: ' . $votersManager->getError();
            }
        } else {
            $_SESSION['info'] = 'No changes made to the voter details.';
        }
    } else {
        $_SESSION['error'] = 'Error fetching voter details';
    }
} else {
    $_SESSION['error'] = 'Invalid request';
}

// Redirect back to voters.php
header('location: voters.php');
exit();
?>
