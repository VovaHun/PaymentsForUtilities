<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_connection.php';

	// Пользователь зарегистрирован
	if ( isset ($_SESSION['logged_main_admin']) )
	{
		$main_admin = $_SESSION['logged_main_admin'];
	}
	// Пользователь не зарегистрирован
	else
	{
		header("Location: login.php");
	}
?>