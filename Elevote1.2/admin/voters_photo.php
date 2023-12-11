<?php
include 'includes/session.php';
include 'includes/header.php';
include_once 'includes/connection.php';
include 'voters_manager.php';

$votersManager = new VotersManager($conn);

if (isset($_POST['upload'])) {
    $id = $_POST['id'];
    $filename = $_FILES['photo']['name'];

    if (!empty($filename)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);
    }

    $votersManager->updateVoterPhoto($id, $filename);
}

// The rest of your HTML and form code
?>
