<?php

require_once("functions.php");

if (isset($_POST['newuser']) && isset($_POST['newpass'])) {

	$newuser = $database->escape_string($_POST['newuser']);
	$newpass = $database->escape_string($_POST['newpass']);
	$newpass = crypt($newpass, SALT); // get SALT from config.php

	$query = "INSERT INTO users (username,password,isadmin) VALUES ('$newuser','$newpass',1)";
	$result = $database->query($query);

	if (isset($result)) {
		echo "<p>User created successfully! You can now login to the Admin Menu with this user to create other users (if necessary). <span style='color: red; font-weight: bold;'>Now go delete this file (install.php)!</span></p>";
	} else {
		echo "<p>Error: User was not created.</p>";
	}

}


echo '

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DSF Initial Installation</title>
</head>
<body>

<p>&nbsp;</p>
<div align="center">
<form action="install.php" method="POST">

	<label for="newuser">Username: </label><input type="text" name="newuser" id="newuser"><br>
	<label for="newpass">Password: </label><input type="password" name="newpass" id="newpass"><br><br>
	<input type="submit" name="submit" value="Create User">

</form>
</div>
	
</body>
</html>

';


?>