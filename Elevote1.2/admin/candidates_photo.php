<?php
include 'includes/session.php';
include 'candidates_manager.php';

if (isset($_POST['upload'])) {
    $id = $_POST['id'];
    $filename = $_FILES['photo']['name'];

    if (!empty($filename)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);
    }

    $candidateManager = new CandidateManager($conn);

    if ($candidateManager->updatePhoto($id, $filename)) {
        $_SESSION['success'] = 'Photo updated successfully';
    } else {
        $_SESSION['error'] = $candidateManager->getError(); // Use the correct meth
    }
} else {
    $_SESSION['error'] = 'Select candidate to update photo first';
}

header('location: candidates.php');
?>
