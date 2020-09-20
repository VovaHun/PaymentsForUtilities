<?php
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/sessionCheck.php';
	unset($_SESSION['logged_user']);
	header('Location: /');
?>
