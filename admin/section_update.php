<?php

session_start();

require_once("../functions.php");

if (!isset($_SESSION['user'])) {
	header("location:login.php");
}

if (isset($_POST['selectId'])) {
	$selectId = $database->escape_string($_POST['selectId']);
}

if (isset($_POST['saveId'])) {
	$saveId = $database->escape_string($_POST['saveId']);
}

require_once("header.php");

if (isset($statusMessage)) {
	echo "<span style='color: green;'>$statusMessage</span><br>";
}

if (isset($selectId)) {
	getSingleSectionForUpdate($selectId);	
} elseif(isset($saveId)) {
	$statusMessage = updateSingleSection($saveId);	
	echo $statusMessage;
} else {
	getAllSectionsForUpdate();	
}

require_once("footer.php");

?>