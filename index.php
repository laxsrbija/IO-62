<?php
/**
 * Created by PhpStorm.
 * User: Lazar
 * Date: 15.3.2017.
 * Time: 19.35
 */

    require_once "functions.php";
    require_once "header.php";

    displayGuestMessage(isset($_SESSION["id"]));

?>
        <br>
        <span class="main">Welcome to <?php echo $appname ?></span>
    </body>
</html>

