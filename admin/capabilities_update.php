<?php

session_start();

require_once("../functions.php");

if (!isset($_SESSION['user'])) {
	header("location:login.php");
}

if (isset($_POST['selectId'])) {
	//$selectId = mysqli_real_escape_string($connection, $_GET['selectId']);
	$selectId = $database->escape_string($_POST['selectId']);
}

if (isset($_POST['saveId'])) {
	//$saveId = mysqli_real_escape_string($connection, $_POST['saveId']);
	$saveId = $database->escape_string($_POST['saveId']);
}

if (isset($_POST['section'])) {
	//$sectionId = mysqli_real_escape_string($connection, $_POST['section']);
	$sectionId = $database->escape_string($_POST['section']);
}

require_once("header.php");

if (isset($statusMessage)) {
	echo "<span style='color: green;'>$statusMessage</span><br>";
}

if (isset($selectId)) {
	getSingleCapabilityForUpdate($selectId);	

	echo '

			<form action="capabilities_update.php" method="POST">

				<div class="form-group">					
				<p>&nbsp;</p>
';

				getAllSectionsForSelection($selectId);

echo '
				<br><label for="section">Place this capability under the above header.</label>
				</div>
				<br>
				<input type="hidden" name="saveId" value="'.$selectId.'">
				<input class="btn btn-primary" type="submit" name="submit" value="Update">

			</form>
	';

} elseif(isset($sectionId) && isset($saveId)) {

	assignToSection($sectionId, $saveId);

	$statusMessage = "Capability header updated successfully! Just a moment...";
	$statusMessage .= "<meta http-equiv='refresh' content='3; url=capabilities_update.php' />";

	echo $statusMessage;

} elseif(isset($saveId)) {
	$statusMessage = updateSingleCapability($saveId);	
	echo $statusMessage;
} else {
	getAllCapabilitiesForUpdate();	
}

require_once("footer.php");

?>