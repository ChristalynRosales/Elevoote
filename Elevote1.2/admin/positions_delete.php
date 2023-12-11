<?php
include 'includes/session.php';
include 'positions_manager.php';

if (isset($_POST['delete'])) {
    $positionObj = new PositionManager($conn);

    $id = $_POST['id'];

    if ($positionObj->deletePosition($id)) {
        $_SESSION['success'] = 'Position deleted successfully';
    } else {
        $_SESSION['error'] = 'Error deleting position: ' . $positionObj->getError();
    }
    var_dump($_SESSION); // Debugging line
} else {
    $_SESSION['error'] = 'Select item to delete first';
}
// Redirect back to candidates.php
header('Location: positions.php');
exit();

?>

