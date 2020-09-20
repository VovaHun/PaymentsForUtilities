<?php
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/sessionCheck.php';
	
	$name = $_POST['name'];
	$userId = $user['UserId'];
	$companyId = $_POST['companyId'];
	$objectId = $_POST['objectId'];
	$queryDate = date("Y-m-d H:i:s");
	$queryStatus = 0;
	$queryAnswer = "";
	
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	$query = "INSERT INTO AccountsQuery (AccountName, UserId, CompanyId, ObjectId, QueryDate, QueryStatus, QueryAnswer) VALUES ('".$name."', '".$userId."', '".$companyId."', '".$objectId."', '".$queryDate."', '".$queryStatus."', '".$queryAnswer."')";
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
	
	header("Location: /index.php");
?>