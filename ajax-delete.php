<?php

    session_start();

    require_once "functions.php";
    $id = "";
    $loggedin = FALSE;

    if (isset($_SESSION["user"])) {
        $userstr = $_SESSION["user"];
        $id = $_SESSION["id"];
        $loggedin = TRUE;
    }

    if (isset($_GET["delete"])) {
        $del = sanitizeString($_GET["delete"]);
        $owner = queryDBO("SELECT user_id, file_path FROM images where id = $del LIMIT 1")->fetch_assoc();

        if ($owner["user_id"] == $id) {
            unlink("images/$id/" . $owner["file_path"]);
            queryDBO("DELETE FROM images where id = $del");
            echo "1";
            die();
        }

    }

    echo "0";