<?php
include 'includes/session.php';
include 'includes/header.php';
include_once 'includes/connection.php';
include 'voters_manager.php';

$votersManager = new VotersManager($conn);

// Assuming you have the form submission logic here
if (isset($_POST['add'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $photo = $_FILES['photo']['name'];

    $votersManager->addVoter($firstname, $lastname, $password, $photo);
}

// The rest of your HTML and form code
?>
