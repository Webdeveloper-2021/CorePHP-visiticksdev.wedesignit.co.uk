<?php

	$admintoken = "";
	$adminpermission = "";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

// LOGOUT ACTIONS

	unset($_SESSION['adminUser']);

	header("location:index.php");
	exit();
?>