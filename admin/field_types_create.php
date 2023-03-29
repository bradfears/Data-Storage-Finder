<?php

session_start();

require_once("../functions.php");

if (!isset($_SESSION['user'])) {
	header("location:login.php");
}

if (isset($_POST['submit'])) {

	$statusMessage = createFieldTypes();

}


require_once("header.php");

if (isset($statusMessage)) {
	echo "<span style='color: green;'>$statusMessage</span><br>";
}

echo '
		<form action="'.$_SERVER['PHP_SELF'].'" method="POST">

			<div class="form-group">
				<label for="field_type">Field type</label>
				<input type="text" name="field_type" class="form-control">
			</div>

			<input class="btn btn-primary" type="submit" name="submit" value="Add">

		</form>
';

require_once("footer.php");

?>