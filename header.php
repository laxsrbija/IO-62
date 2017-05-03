<?php

    session_start();

    require_once "functions.php";
    $userstr = "Guest";
    $id = "";
    $loggedin = FALSE;

    if (isset($_SESSION["user"])) {
        $userstr = $_SESSION["user"];
        $id = $_SESSION["id"];
        $loggedin = TRUE;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $appname . " - " . $userstr ?></title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body style="padding-bottom: 15px;">
        <div class="container">
            <canvas id="logo" width="624" height="96"><?php echo $appname ?></canvas>
        </div>
        <div class="appname"><?php echo $appname . " - " . $userstr ?></div>
        <script type="text/javascript" src="javascript.js"></script>
        <ul class="menu">
            <?php

                if (!isset($_SESSION["id"])) {
                    echo '<li><a href="index.php">Home</a></li> ';
                    echo '<li><a href="signup.php">Sign up</a></li> ';
                    echo '<li><a href="login.php">Log in</a></li> ';
                } else {
                    echo '<li><a href="index.php?view=' . $id . '">Home</a></li> ';
                    echo '<li><a href="members.php">Members</a></li> ';
                    echo '<li><a href="gallery.php?user=' . $id . '">Gallery</a></li> ';
                    echo '<li><a href="friends.php">Friends</a></li> ';
                    echo '<li><a href="messages.php">Messages</a></li> ';
                    echo '<li><a href="profile.php">Edit Profile</a></li> ';
                    echo '<li><a href="logout.php">Log out</a></li> ';
                }

            ?>
        </ul>
        <br>