<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script>

    function ajaxRequest() {

        try {
            var request = new XMLHttpRequest();
        } catch (e1) {
            request = new ActiveXObject("Msxm12.XMLHTTP");
        } /*catch (e2) {
            request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e3) {
            request = false;
        }*/

        return request;

    }

    function checkUser(user) {

        if (user.value === "") {
            document.getElementById("info").innerHTML = "";
            return;
        }

        var request = ajaxRequest();
        var params = "user=" + user.value;
        request.open("POST", "ajax-checkuser.php", true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                document.getElementById("info").innerHTML = this.responseText;
            }
        };

        request.send(params);

    }

    function checkUserJQ(user) {

        if (user.value === "") {
            document.getElementById("info").innerHTML = "";
            return;
        }

        $.ajax({
            method: "POST",
            url: "ajax-checkuser.php",
            data: {
                'user': user.value
            },
            success: function(result) {
                document.getElementById("info").innerHTML = result;
            }
        });

    }

</script>
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
            $result = queryDBO("select * from members where user = '$user'");
            if ($result->num_rows) {
                $error = "That username is already taken.<br><br>";
            } else {
                $hpass = hash('ripemd128', "$salt1$pass$salt2");
                queryDBO("insert into members (user, pass) values ('$user', '$hpass')");
                die("<h4>Account created. You may now log in.</h4>");
            }
        }

    }

    displayGuestMessage(isset($_SESSION["id"]))

?>
<form method="post" action="">
    <?php echo "<h4>$error</h4>" ?>
    <label class="fieldname" for="user">Username:</label>
    <input type="text" name="user" id="user" value="" maxlength="16" onBlur="checkUserJQ(this)">
    <span id="info"></span>
    <br>
    <label class="fieldname" for="pass">Password:</label>
    <input type="password" name="pass" id="pass" value="" maxlength="64">
    <br>
    <label class="fieldname">&nbsp;</label>
    <input type="submit" value="Sign up">
</form>
</body>
</html>