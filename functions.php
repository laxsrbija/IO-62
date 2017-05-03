<?php
/**
 * Created by PhpStorm.
 * User: Lazar
 * Date: 15.3.2017.
 * Time: 18.54
 */

    $dbhost = "localhost";
    $dbname = "robinsnest";
    $dbuser = "robinsnest";
    $dbpass = "lax";
    $appname = "Robin's Nest";

    $salt1 = "78asd*A";
    $salt2 = "#456;%";

    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection->connect_error) {
        die($connection->connect_error);
    }

    function queryDBO($q) {
        global $connection;
        $result = $connection->query($q);
        if (!$result) die($connection->error);
        return $result;
    }

    function createTable($name, $q) {
        queryDBO("CREATE TABLE IF NOT EXISTS $name($q)");
        echo "Table '$name' created or already exists.<br>";
    }

    function sanitizeString($var) {

        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);

        global $connection;
        return $connection->real_escape_string($var);

    }

    function displayGuestMessage($s) {
        if (!$s) echo '<span class="info">&#8658; You must be logged in to view this site.</span>';
    }

    function destroySession() {
        session_unset();
        session_destroy();

        header("Location: .");
        die();
    }

    function showProfile($id) {
        $result = queryDBO("SELECT * FROM profiles WHERE user_id = $id");
        if ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (file_exists("images/$id/$id.jpg")) {
                echo "<img src='images/$id/$id.jpg' style='float:left;'>";
            }
            echo stripslashes($row["text"]);
            echo "<br><br>";
        }
    }