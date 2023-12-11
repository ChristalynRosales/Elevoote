<?php include 'includes/session.php';?>
<?php include 'candidates_manager.php'; ?> <!--CandidateManager class-->
<!--Create a Database instance-->
<?php 
$database = new DatabaseConnection();
$conn = $database->getConnection();

if (isset($_POST['add'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $platform = $_POST['platform'];
    $filename = $_FILES['photo']['name'];

    error_log("Received form data - Firstname: $firstname, Lastname: $lastname, Position: $position, Platform: $platform, Filename: $filename");

    if (!empty($filename)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);
        
    }
    // Debugging: Check if file is moved successfully
    if (!empty($filename)) {
        error_log("File moved successfully to ../images/$filename");
    }

    // Pass the PDO connection to the CandidateManager constructor
    $candidateManager = new CandidateManager($conn);

    if ($candidateManager->addCandidate($firstname, $lastname, $position, $platform, $filename)) {
        $_SESSION['success'] = 'Candidate added successfully';
    } else {
        $_SESSION['error'] = $candidateManager->getError();
        error_log("Error adding candidate: " . $candidateManager->getError());
    }
}
// Debugging: Output redirection header
error_log("Redirecting to candidates.php");
header('location: candidates.php');
exit();
?>
