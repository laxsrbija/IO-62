<?php

    require_once "header.php";

    if (!isset($_GET["user"])) {
        header("Location: index.php");
        die();
    }

    if (isset($_GET["delete"])) {

    }

    if (isset($_FILES["image"]["name"])) {

        if (!file_exists("images/$id")) {
            mkdir("images/$id");
        }

        $filename = time() . $_FILES["image"]["name"] . ".jpg";

        $saveto = "images/$id/$filename";
        move_uploaded_file($_FILES["image"]["tmp_name"], $saveto);

        $src = null;

        switch($_FILES["image"]["type"]) {
            case "image/gif":
                $src = imagecreatefromgif($saveto);
                break;
            case "image/jpeg": case "image/pjpeg":
            $src = imagecreatefromjpeg($saveto);
            break;
            case "image/png":
                $src = imagecreatefrompng($saveto);
                break;
        }

        if ($src != null) {
            list($w, $h) = getimagesize($saveto);
            $max = 500;
            $tw = $w;
            $th = $h;

            if ($w > $h && $w > $max) {
                $tw = $max;
                $th = $max / $w * $h;
            } elseif ($h > $w && $h > $max) {
                $th = $max;
                $tw = $max / $h * $w;
            } elseif ($w > $max) {
                $tw = $th = $max;
            }

            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(array(-1, -1, -1), array(-1, 16, 1,), array(-1, -1, -1)), 8, 0);
            imagejpeg($tmp, $saveto, 100);

            imagedestroy($tmp);
            imagedestroy($src);

            queryDBO("INSERT INTO images (user_id, file_path, date_uploaded) VALUES ($id, '$filename', NOW())");

        }

    }

    $u = sanitizeString($_GET["user"]);
    $display = queryDBO("SELECT user FROM members where id = $u LIMIT 1")->fetch_assoc();

?>
<div class="main">
    <h3><?php echo $display["user"] ?>'s Gallery</h3>
    <?php

        if ($u == $id) {
            echo '
                <form method="post" action="" enctype="multipart/form-data">
                    <input type="file" name="image" id="image">
                    <input type="submit" value="Upload Image">
                </form><br><br>
            ';
        }

        $images = queryDBO("select id, file_path, DATE_FORMAT(date_uploaded,'%d %b %Y %T') as date_uploaded from images where user_id = $u");

        if ($images->num_rows == 0) {
            echo "<h1>No images to display</h1>";
        }

        for ($i = 0; $i < $images->num_rows; $i++) {
            $row = $images->fetch_assoc();
            echo "<div id='" . $row["id"] . "'><img src='images/$u/" . $row["file_path"] . "'><br><span>" . $row["date_uploaded"] . "</span>";
            if ($u == $id) {
                echo " <a href='javascript:del(" . $row["id"] . ")'>Delete Image</a>";
            }
            echo "<br><br></div>";
        }

    ?>

</div>
</body>
</html>