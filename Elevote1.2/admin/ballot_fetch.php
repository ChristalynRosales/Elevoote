<?php
include 'includes/session.php';
include 'ballot_manager.php';

$ballotManager = new BallotManager($conn);

echo $ballotManager->fetchBallot();
?>
