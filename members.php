<?php
/**
 * Created by PhpStorm.
 * User: Lazar
 * Date: 29.3.2017.
 * Time: 19.42
 */

    require_once "header.php";
    if (!$loggedin) die();

?>
    <div class="main">
        <?php

            if (isset($_GET["id"])) {

                $mid = sanitizeString($_GET["id"]);
                $result = queryDBO("SELECT * FROM members WHERE id = $mid");
                if ($result->num_rows) {
                    $row = $result->fetch_assoc();
                    $view = $row["user"];
                } else {
                    $view = "";
                }

                if ($view == $userstr) {
                    $name = "Your";
                } else {
                    $name = "$view's";
                }

                echo "<h3>$name Profile</h3>";
                showProfile($mid);
                die("</div></body></html>");

            }

            if (isset($_GET["add"])) {
                $add = sanitizeString($_GET["add"]);
                $result = queryDBO("SELECT * FROM friends WHERE user_id = $add AND friend_id = $id");
                if (!$result->num_rows) {
                    queryDBO("INSERT INTO friends (user_id, friend_id) VALUES ($add, $id)");
                }
            }

            if (isset($_GET["remove"])) {
                $remove = sanitizeString($_GET["remove"]);
                $result = queryDBO("SELECT * FROM friends WHERE user_id = $remove AND friend_id = $id");
                if ($result->num_rows) {
                    queryDBO("DELETE FROM friends WHERE user_id = $remove AND friend_id = $id");
                }
            }

        ?>
        <ul>
            <?php

                $result = queryDBO("SELECT id, user FROM members WHERE id != $id");
                $num = $result->num_rows;

                for ($i = 0; $i < $num; $i++) {

                    $row = $result->fetch_assoc();
                    echo "<li><a href='members.php?id=" . $row["id"] . "'>" . $row["user"] . "</a> ";

                    $follow = "follow";

                    $result1 = queryDBO("SELECT * FROM friends WHERE user_id = " . $row["id"] . " AND friend_id = $id");
                    $t1 = $result1->num_rows;

                    $result2 = queryDBO("SELECT * FROM friends WHERE user_id = $id AND friend_id = " . $row["id"]);
                    $t2 = $result2->num_rows;

                    if ($t1 + $t2 > 1) {
                        echo "&harr; is a mutual friend ";
                    } elseif ($t1 > 0) {
                        echo "&larr; you are following ";
                    } elseif ($t2 > 0) {
                        echo "&rarr; is following you ";
                        $follow .= " back";
                    }

                    if (!$t1) {
                        echo "[<a href='members.php?add=" . $row["id"] . "'>$follow</a>]";
                    } else {
                        echo "[<a href='members.php?remove=" . $row["id"] . "'>un$follow</a>]";
                    }

                    echo "</li>";

                }

            ?>
        </ul>
    </div>
</body>
</html>
