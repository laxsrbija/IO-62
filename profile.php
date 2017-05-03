<?php
    require_once "header.php";
    if (!$loggedin) die();
?>
<div class="main">
    <h3>Your Profile</h3>
    <?php

    $fname = $lname = $email = $gender = "";
    $favLang = array();
    $fnameError = $lnameError = $emailError = $genderError = $favLangError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $required = " is required.";
        $unallowed = "Unallowed characters in ";

        if (empty($_POST["fname"])) {
            $fnameError = "First name" . $required;
        } else {
            if (!ctype_alpha($_POST["fname"])) {
                $fnameError = $unallowed . "first name";
            } else {
                $fname = sanitizeString($_POST["fname"]);
            }
        }

        if (empty($_POST["lname"])) {
            $lnameError = "Last name" . $required;
        } else {
            if (!ctype_alpha($_POST["lname"])) {
                $lnameError = $unallowed . "last name";
            } else {
                $lname = sanitizeString($_POST["lname"]);
            }
        }

        if (empty($_POST["email"])) {
            $emailError = "Email" . $required;
        } else {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format.";
            } else {
                $email = sanitizeString($_POST["email"]);
            }
        }

        if (empty($_POST["gender"])) {
            $genderError = "Gender" . $required;
        } else {
            $gender = sanitizeString($_POST["gender"]);
        }

        if (empty($_POST["favLang"])) {
            $favLangErrorError = "Favourite language" . $required;
        } else {
            $favLang = $_POST["favLang"];
        }

    }

        $result = queryDBO("SELECT * FROM profiles WHERE user_id = $id");
        if (isset($_POST["text"])) {
            $text = sanitizeString($_POST["text"]);
            $text = preg_replace('/\s+/', " ", $text);
            if ($result->num_rows) {
                queryDBO("UPDATE profiles SET text = '$text' WHERE user_id = $id");
            } else {
                queryDBO("INSERT INTO profiles (user_id, text) VALUES ($id, '$text')");
            }
        } else {
            if ($result->num_rows) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $text = stripslashes($row["text"]);
            } else {
                $text = "";
            }
        }

        $text = stripslashes(preg_replace('/\s+/', " ", $text));

        if (isset($_FILES["image"]["name"])) {

            if (!file_exists("images/$id")) {
                mkdir("images/$id");
            }

            $saveto = "images/$id/$id.jpg";
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
                $max = 100;
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

            }
        }

        showProfile($id);

    ?>
    <form method="post" action="" enctype="multipart/form-data">
        <h3>Edit your details or change your profile picture.</h3>
        <span class="error">* Required fields</span><br><br>
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name = "fname" value="<?php echo $fname ?>" required>
        <span class='error'>* <?php echo $fnameError ?></span>
        <br><br>
        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" value="<?php echo $lname ?>" required>
        <span class='error'>* <?php echo $lnameError ?></span>
        <br><br>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo $email ?>" required>
        <span class='error'>* <?php echo $emailError ?></span>
        <br><br>
        <label for="gender">Gender:</label>
        <input type="radio" name="gender" value="Male" <?php if (isset($gender) && $gender == "Male") echo "checked" ?>>Male
        <input type="radio" name="gender" value="Female"  <?php if (isset($gender) && $gender == "Female") echo "checked" ?>>Female<br><br>

        <select name="favLang[]" multiple>
            <?php
            $options = array("php", "c", "c++", "java", "python");
            foreach ($options as $option) {
                echo "<option value='$option'";
                if (in_array($option, $favLang)) {
                    echo "selected";
                }
                echo ">" . ucfirst($option) . "</option>";
            }
            ?>
        </select>

        <textarea name="text" cols="50" rows="3"><?php echo $text ?></textarea>
        <br>
        <label for="image">Image</label>
        <input type="file" name="image" id="image">
        <input type="submit" value="Save Profile">
    </form>
</div>
</body>
</html>

