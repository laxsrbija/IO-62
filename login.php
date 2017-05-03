<?php

require_once "header.php";

if (isset($_SESSION["id"])) {
    header('Location: index.php');
}

$user = $pass = $error = "";

if (isset($_POST["user"])) {
    $user = sanitizeString($_POST["user"]);
    $pass = $_POST["pass"];
    if ($user == "" || $pass == "") {
        $error = "Not all fields were entered<br><br>";
    } else {
        $hpass = hash("ripemd128", "$salt1$pass$salt2");
        $result = queryDBO("select * from members where user = '$user'");
        if ($result->num_rows == 0) {
            $error = "<span class='error'>User not found</span><br><br>";
        } else {
            $row = $result->fetch_assoc();
            if ($row["pass"] != $hpass) {
                $error = "<span class='error'>Invalid password</span><br><br>";
            } else {
                $id = $row["id"];
                $_SESSION["id"] = $id;
                $_SESSION["user"] = $user;
                $error = "Successfully logged in.<br>Please <a href='index.php?view=$id'>click here</a> to continue.<br><br>";
            }
        }
    }
}

displayGuestMessage(isset($_SESSION["id"]));

?>
<h4><?php echo $error ?></h4>
<?php

if (!isset($_SESSION["id"])) {
    echo '<form method="post" action="">
                    
                    <label class="fieldname" for="user">Username:</label>
                    <input type="text" name="user" id="user" value="" maxlength="16">
                    <br>
                    <label class="fieldname" for="pass">Password:</label>
                    <input type="password" name="pass" id="pass" value="" maxlength="64">
                    <br>
                    <label class="fieldname">&nbsp;</label>
                    <input type="submit" value="Log in">
                </form>';
}

?>
</body>
</html>
