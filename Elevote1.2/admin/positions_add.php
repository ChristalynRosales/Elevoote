<?php
include 'includes/session.php';
include 'positions_manager.php';

if (isset($_POST['add'])) {
    $positionObj = new PositionManager($conn);
    $description = $_POST['description'];
    $maxVote = $_POST['max_vote'];

    if ($positionObj->addPosition($description, $maxVote)) {
        $_SESSION['success'] = 'Position added successfully';
    } else {
        $_SESSION['error'] = 'Error adding position: ' . $positionObj->getError();
    }

} else {
    $_SESSION['error'] = 'Fill up add form first';
}

header('location: positions.php');
?>
