<?php
	require 'connection.php';

	// Пользователь зарегистрирован
	if ( isset ($_SESSION['logged_user']) )
	{
		$user = $_SESSION['logged_user'];
	}
	// Пользователь не зарегистрирован
	else
	{
		header("Location: /login.php");
	}
?>