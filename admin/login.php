<?php

session_start();

require_once("../functions.php");

if (isset($_POST['submit'])) {

	userLogin();

}


require_once("header.php");

echo '

		<form action="login.php" method="POST">

			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" name="username" class="form-control">
			</div>

			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" name="password" class="form-control">
			</div>

			<input class="btn btn-primary" type="submit" name="submit" value="Submit">

		</form>

';

require_once("footer.php");
?>