<?php
	require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_connection.php';

	// Пользователь зарегистрирован
	if ( isset ($_SESSION['logged_company_admin']) )
	{
		$company_admin = $_SESSION['logged_company_admin'];
	}
	// Пользователь не зарегистрирован
	else
	{
		header("Location: login.php");
	}
?>