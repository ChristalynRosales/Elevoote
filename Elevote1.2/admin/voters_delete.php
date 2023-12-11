<?php
include 'includes/session.php';
include 'includes/header.php';
include_once 'includes/connection.php';
include 'voters_manager.php';

$votersManager = new VotersManager($conn);

if (isset($_POST['delete'])) {
    
    $id = $_POST['id'];

    if ($votersManager->deleteVoter($id)) {
        $_SESSION['success'] = 'Voter deleted successfully';
    } else {
        $_SESSION['error'] = 'Error deleting voter: ' . $votersManager->getError();
    }
} else {
    $_SESSION['error'] = 'Select item to delete first';
}

// Redirect back to voters.php
header('location: voters.php');
exit();
?>

