<?php
include 'includes/session.php';
include 'ballot_manager.php';

$ballotManager = new BallotManager($conn);

if (isset($_POST['id'])) {
    echo $ballotManager->movePositionUp($_POST['id']);
}
?>
