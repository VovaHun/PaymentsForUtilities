<?php
	require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_sessionCheck.php';
	unset($_SESSION['logged_company_admin']);
	header('Location: login.php');
?>
