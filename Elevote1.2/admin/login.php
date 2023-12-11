<?php
session_start();
include 'includes/connection.php';

// $databaseConnection is an instance of DatabaseConnection from connection.php
$databaseConnection = new DatabaseConnection();
$conn = $databaseConnection->getConnection();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() < 1) {
        $_SESSION['error'] = 'Cannot find account with the username';
    } else {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $row['id'];
        } else {
            $_SESSION['error'] = 'Incorrect password';
        }
    }

} else {
    $_SESSION['error'] = 'Input admin credentials first';
}

header('location: index.php');
?>
