<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['login'])) {
    $voter = $_POST['voter'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM voters WHERE voters_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $voter);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC); // Use PDO fetch method

    if (!$row) {
        $_SESSION['error'] = 'Cannot find voter with the ID';
    } else {
        // Verify password using password_verify
        if (password_verify($password, $row['password'])) {
            $_SESSION['voter'] = $row['id'];
            // Redirect to a welcome page after successful login
            //header('location: welcome.php');
            //exit();
        } else {
            $_SESSION['error'] = 'Incorrect password';
        }
    }
} else {
    $_SESSION['error'] = 'Input voter credentials first';
}

// Redirect to the appropriate page, whether there was an error or not
header('location: index.php');
exit();
?>
