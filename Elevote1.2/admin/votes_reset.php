<?php
include 'includes/session.php';

if (!class_exists('DatabaseConnection')) {
    include 'includes/connection.php'; 
include 'includes/connection.php';
}

$databaseConnection = new DatabaseConnection();
$conn = $databaseConnection->getConnection();

$sql = "DELETE FROM votes";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $_SESSION['success'] = "Votes reset successfully";
} catch (PDOException $e) {
    $_SESSION['error'] = "Something went wrong in resetting: " . $e->getMessage();
}

header('location: votes.php');
