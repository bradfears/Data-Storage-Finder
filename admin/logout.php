<?php

session_start();

require_once("../functions.php");

session_unset();
session_destroy();

header("location:login.php");

?>