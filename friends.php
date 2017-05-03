<?php

    require_once "header.php";

    if (!$loggedin) {
        header("Location: .");
        die();
    }

    if (isset($_GET["id"])) {
        $mid = sanitizeString($_GET["id"]);
    } else {
        $mid = $id;
    }

    $result = queryDBO("SELECT * FROM members WHERE id = $mid");

    if ($result->num_rows) {
        $row = $result->fetch_assoc();
        $view = $row["user"];
    } else {
        $view = "";
    }

    if ($view == $userstr) {
        $name1 = $name2 = "Your";
        $name3 = "You are";
    } else {
        $name1 = "<a href='members.php?id=$mid'>$view's</a>";
        $name2 = "$view's";
        $name3 = "$view is";
    }

?>
<div class="main">
    <?php

        $followers = array();
        $following = array();

        $result = queryDBO("SELECT * FROM friends WHERE user_id = $mid");
        $num = $result->num_rows;

        for ($j = 0; $j < $num; $j++) {
            $row = $result->fetch_assoc();
            $followers[$j] = $row["friend_id"];
        }

        $result = queryDBO("SELECT * FROM friends WHERE friend_id = $mid");
        $num = $result->num_rows;

        for ($j = 0; $j < $num; $j++) {
            $row = $result->fetch_assoc();
            $following[$j] = $row["user_id"];
        }

        $mutual = array_intersect($followers, $following);
        $followers = array_diff($followers, $mutual);
        $following = array_diff($following, $mutual);

        $friends = (sizeof($mutual) || sizeof($followers) || sizeof($following));

        if (sizeof($mutual)) {
            echo "<span class='subhead'>$name1 mutual friends</span>";
            echo "<ul>";
            foreach ($mutual as $fr_id) {
                $result = queryDBO("SELECT * FROM members WHERE id = $fr_id");
                $row = $result->fetch_assoc();
                $fname = $row["user"];
                echo "<li><a href='members.php?id=$fr_id'>$fname</a></li>";
            }
            echo "</ul>";
        }

        if (sizeof($followers)) {
            echo "<span class='subhead'>$name2 followers</span>";
            echo "<ul>";
            foreach ($followers as $fr_id) {
                $result = queryDBO("SELECT * FROM members WHERE id = $fr_id");
                $row = $result->fetch_assoc();
                $fname = $row["user"];
                echo "<li><a href='members.php?id=$fr_id'>$fname</a></li>";
            }
            echo "</ul>";
        }

        if (sizeof($following)) {
            echo "<span class='subhead'>$name3 following</span>";
            echo "<ul>";
            foreach ($following as $fr_id) {
                $result = queryDBO("SELECT * FROM members WHERE id = $fr_id");
                $row = $result->fetch_assoc();
                $fname = $row["user"];
                echo "<li><a href='members.php?id=$fr_id'>$fname</a></li>";
            }
            echo "</ul>";
        }

        if (!$friends) {
            echo "<img src='http://i43.tinypic.com/22nodi.png'>";
        }

    ?>
</div>
