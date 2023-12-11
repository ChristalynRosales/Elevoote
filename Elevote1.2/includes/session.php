<?php
include 'includes/conn.php';
session_start();

if(isset($_SESSION['voter'])){
    $sql = "SELECT * FROM voters WHERE id = :voter_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':voter_id', $_SESSION['voter']);
    $stmt->execute();

    $voter = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$voter) {
        // Handle the case where the voter is not found
        header('location: index.php');
        exit();
    }
}
else{
    header('location: index.php');
    exit();
}
?>
