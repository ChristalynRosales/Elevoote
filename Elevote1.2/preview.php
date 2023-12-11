<?php
include 'includes/session.php';
include 'includes/slugify.php';

error_log('Script is running.');

$output = array('error' => false, 'list' => '');

try {
    $conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM positions";
    $query = $conn->query($sql);

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $position = slugify($row['description']);
        $pos_id = $row['id'];
        
        if (isset($_POST[$position])) {
            if ($row['max_vote'] > 1) {
                if (count($_POST[$position]) > $row['max_vote']) {
                    $output['error'] = true;
                    $output['message'][] = '<li>You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'] . '</li>';
                } else {
                    foreach ($_POST[$position] as $key => $values) {
                        $sql = "SELECT * FROM candidates WHERE id = :id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':id', $values, PDO::PARAM_INT);
                        $stmt->execute();
                        $cmrow = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        $output['list'] .= "
                            <div class='row votelist'>
                                <span class='col-sm-4'><span class='pull-right'><b>" . $row['description'] . " :</b></span></span> 
                                <span class='col-sm-8'>" . $cmrow['firstname'] . " " . $cmrow['lastname'] . "</span>
                            </div>
                        ";
                    }
                }
            } else {
                $candidate = $_POST[$position];
                $sql = "SELECT * FROM candidates WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $candidate, PDO::PARAM_INT);
                $stmt->execute();
                $csrow = $stmt->fetch(PDO::FETCH_ASSOC);

                $output['list'] .= "
                    <div class='row votelist'>
                        <span class='col-sm-4'><span class='pull-right'><b>" . $row['description'] . " :</b></span></span> 
                        <span class='col-sm-8'>" . $csrow['firstname'] . " " . $csrow['lastname'] . "</span>
                    </div>
                ";
            }
        }
    }
} catch (PDOException $e) {
    $output['error'] = true;
    $output['message'][] = 'Error: ' . $e->getMessage();
}

echo json_encode($output);
?>
