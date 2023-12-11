<?php
include 'includes/session.php';
include 'positions_manager.php';

if (isset($_POST['edit'])) {
    $positionObj = new PositionManager($conn);
    $id = $_POST['id'];
    $description = $_POST['description'];
    $maxVote = $_POST['max_vote'];

    if ($positionObj->updatePosition($id, $description, $maxVote)) {
        $_SESSION['success'] = 'Position updated successfully';
    } else {
        $_SESSION['error'] = 'Error updating position: ' . $positionObj->getError();
    }
} else {
    $_SESSION['error'] = 'Fill up edit form first';
}

header('location: positions.php');
?>
