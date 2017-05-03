<?php
	require_once 'header.php';
	$user = $pass = $error = '';
	if(isset($_POST['user'])){
		$user = sanitizeString($_POST['user']);
		$pass = sanitizeString($_POST['pass']);
		if($user == '' || $pass == ''){
			$error = "Not all fields were entered<br>";
		}
		else{
			$hpass = hash('ripemd128', "$salt1$pass$salt2");
			$result = queryDBO("SELECT * FROM members WHERE user = '$user'");
			if($result->num_rows == 0){
				$error = "<span class='error'>Username invalid</span><br>";
			}
			else{
				$row = $result->fetch_assoc();
				if($row['pass'] != $hpass){
					$error = "<span class='error'>Password invalid</span><br>";
				}
				else{
					$id = $row['id'];
					$_SESSION['id'] = $id;
					$_SESSION['user'] = $user;
					die("You are now logged in. Please <a href='members.php?view=$id'>click here</a> to continue.<br>");
				}
			}
		}
	}
?>
	
		<form method="post" action="login.php">
			<?php echo $error ?>
			<br>
			<label class="fieldname" for="user">Username</label>
			<input type="text" name="user" id="user" value="<?php echo $user?>" maxlength="16"><br>
			
			<label class="fieldname" for="pass">Password</label>
			<input type="password" name="pass" id="pass" value="<?php echo $pass?>" maxlength="16"><br>
			
			<label class="fieldname">&nbsp;</label> <!-- za poravnanje -->
			<input type="submit" value="Log in">
			
		</form>
	</body>
</html>