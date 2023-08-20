<?php
$filepath = realpath(dirname(__FILE__));
include_once $filepath . '/../lib/session.php';

session::init();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register System Using Raw Php</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- <link rel="stylesheet" href="./Inc/bootstrap.min.css">
    <script src="./Inc/jquery.min.js"></script>
    <script src="./Inc/bootstrap.min.js"></script> -->
</head>
<?php
if (isset($_GET['action']) && $_GET['action'] == "logout") {
    session::destroy();
}
?>

<body>
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">Login Register system PHP & PDO</a>
                </div>
                <ul class="nav navbar-nav pull-right">

                    <?php
                    $id = session::get("id");
                    $userlogin = session::get("login");
                    if ($userlogin == true) {
                    ?>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="profile.php?id=<?php echo $id; ?>">Profile</a></li>
                        <li><a href="?action=logout">Logout</a></li>
                    <?php } else { ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php } ?>
                </ul>
            </div>
        </nav>