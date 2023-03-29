<?php

session_start();

require_once("../functions.php");

if (!isset($_SESSION['user'])) {
	header("location:login.php");
}

if (isset($_POST['submit'])) {

	$statusMessage = createSections();

}


require_once("header.php");

if (isset($statusMessage)) {
	echo "<span style='color: green;'>$statusMessage</span><br>";
}

echo '

		<form action="section_create.php" method="POST">

			<div class="form-group">
				<label for="section">Section</label>
				<input type="text" name="section" class="form-control">
				<br>
				<label for="description">Description</label>
				<textarea id="description" name="description" rows="1" cols="1" class="ckeditor"></textarea>
				<i>Description</i> will appear when the user clicks the question mark icon next to the section question.<br><br>
			</div>

			<input class="btn btn-primary" type="submit" name="submit" value="Add">

		</form>
';

require_once("footer.php");

?>