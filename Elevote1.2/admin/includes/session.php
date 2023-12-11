<?php
session_start();
include 'includes/connection.php';

// Assuming $databaseConnection is an instance of DatabaseConnection from connection.php
$conn = $databaseConnection->getConnection();

if (!isset($_SESSION['admin']) || trim($_SESSION['admin']) == '') {
    header('location: index.php');
}

$sql = "SELECT * FROM admin WHERE id = :admin_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':admin_id', $_SESSION['admin']);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
