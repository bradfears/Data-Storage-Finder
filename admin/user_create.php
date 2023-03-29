<?php

session_start();

require_once("../functions.php");

if (!isset($_SESSION['user'])) {
	header("location:login.php");
}

if (isset($_POST['submit'])) {

	$statusMessage = createUser();

}


require_once("header.php");

if (isset($statusMessage)) {
	echo "<span style='color: green;'>$statusMessage</span><br>";
}

echo '

		<form action="'.$_SERVER['PHP_SELF'].'" method="POST">

			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" name="username" class="form-control">
				<br>
				<label for="password">Password</label>
				<input type="password" name="password" class="form-control">
			</div>

			<input class="btn btn-primary" type="submit" name="submit" value="Add">

		</form>
';

require_once("footer.php");

?>