<?php

session_start();

require_once("../functions.php");

if (!isset($_SESSION['user'])) {
	header("location:login.php");
}

if (isset($_POST['submit'])) {

	$statusMessage = deleteSection();

}


require_once("header.php");

if (isset($statusMessage)) {
	echo "<span style='color: green;'>$statusMessage</span><br>";
}

echo '

		<form action="'.$_SERVER['PHP_SELF'].'" method="POST">

			<div class="form-group">
			<p>&nbsp;</p>';

			getAllSectionsForDelete();

echo '	
				<br><br><input type="checkbox" id="deleteme" name="deleteme" value="1">&nbsp;
				<label for="deleteme">Yes I am sure!</label>
			</div>

			<input class="btn btn-primary" type="submit" name="submit" value="Delete">

		</form>
';

require_once("footer.php");

?>