<?php
include "./Inc/header.php";
include "./lib/User.php";
?>

<?php
if (isset($_GET['id'])) {
    $userid = (int)$_GET['id'];
    $user = new User();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $updateusr = $user->updateUserData($userid, $_POST);
    } elseif (isset($_POST['changepass'])) {
        // Handle password change here
        // You'll need to implement the logic for changing the password
        // You might want to create a new method in your User class for this.
        $changepass = $user->changePassword($userid, $_POST['new_password']);
    }
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2>User Profile<span class="pull-right"><a class="btn btn-primary" href="index.php">Back</a></span></h2>
    </div>
    <div class="panel-body">
        <div style="max-width: 600px; margin:0 auto;">
            <?php
            if (isset($updateusr)) {
                echo $updateusr;
            }
            if (isset($changepass)) {
                echo $changepass;
            }
            ?>
            <?php
            $userdata = $user->getUserById($userid);
            if ($userdata) {
            ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo $userdata->name; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo $userdata->username; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" id="email" name="email" class="form-control" value="<?php echo $userdata->email; ?>" />
                    </div>
                    <?php
                    $sesId = session::get("id");
                    if ($userid == $sesId) {
                    ?>
                        <button type="submit" name="update" class="btn btn-success">Update</button>
                    <?php } ?>
                </form>
                <hr>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" />
                    </div>
                    <button type="submit" name="changepass" class="btn btn-info">Change Password</button>
                </form>
            <?php } ?>
        </div>
    </div>
</div>

<?php include "./Inc/footer.php"; ?>