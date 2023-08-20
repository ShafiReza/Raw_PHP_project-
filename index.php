<?php

?>
<?php

include "./lib/User.php";

include "./Inc/header.php";


session::checksession();
//session::checklogin();

?>
<?php
$loginmsg = session::get("loginmsg");
if (isset($loginmsg)) {
    echo $loginmsg;
}
session::set("loginmsg", NULL);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2>User list<span class="pull-right">Welcome!<strong>
                    <?php
                    $name = session::get("username");
                    if (isset($name)) {
                        echo $name;
                    }
                    ?>
                </strong></span></h2>
    </div>
    <div class="panel-body">
        <table class="table table-striped">

            <th width="20%">Serial</th>
            <th width="20%">Name</th>
            <th width="20%">Username</th>
            <th width="20%">Email Address</th>
            <th width="20%">Action</th>
            <?php
            $user = new User();
            $userdata = $user->getUserData();
            if ($userdata) {
                $i = 0;
                foreach ($userdata as $data) {
                    $i++;

            ?>

                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $data['username']; ?></td>
                        <td><?php echo $data['email']; ?></td>
                        <td>

                            <a class="btn btn-info" href="update.php?id=<?php echo $data["id"]; ?>">View</a>
                            <a class="btn btn-info" href="profile.php?id=<?php echo $data["id"]; ?>">Profile</a>



                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="5">
                        <h2>No User Data Found......</h2>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php include "./Inc/footer.php"; ?>