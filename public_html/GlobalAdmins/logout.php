<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';
	unset($_SESSION['logged_main_admin']);
	header('Location: login.php');
?>
