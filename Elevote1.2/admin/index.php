<?php
session_start();

// Assuming $databaseConnection is an instance of DatabaseConnection from connection.php
include 'includes/connection.php';
$databaseConnection = new DatabaseConnection();
$conn = $databaseConnection->getConnection();

if (isset($_SESSION['admin'])) {
    header('location: home.php');
    exit(); // Make sure to exit after sending headers
}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>Election System</b>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="login.php" method="POST">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="login">
                            <i class="fa fa-sign-in"></i> Sign In
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php
        // Check if $_SESSION['error'] is set before calling displayError
        if (isset($_SESSION['error'])) {
            displayError($_SESSION['error']);
            unset($_SESSION['error']);
        }
        ?>
    </div>

    <?php include 'includes/scripts.php' ?>
</body>
</html>

<?php
function displayError($error)
{
    echo "
        <div class='callout callout-danger text-center mt20'>
            <p>$error</p> 
        </div>
    ";
}
?>
