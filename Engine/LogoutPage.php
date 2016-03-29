<?php
	SESSION_START();

	if (isset($_SESSION["loggedUser"])){
		$_SESSION["loggedUser"] = null;
		unset($_SESSION["loggedUser"]);
	}
	
	header('Location:../index.php?Page=Public%2FHomePage');
?>