<?php
include 'includes/session.php';
include 'positions_manager.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $positionObj = new PositionManager($conn);
    $row = $positionObj->getPositionById($id);
    echo json_encode($row);
}
?>
