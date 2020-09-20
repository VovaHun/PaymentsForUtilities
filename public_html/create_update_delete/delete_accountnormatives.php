<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$res_id = $_POST["normAccountId"];
	$res_table = "AccountNormatives";
	$res_serviceId = $_POST["serviceId"];
	$res_tariffId = $_POST["tariffId"];
	$res_date = $_POST["dateId"];
	
	

	//Запрос на название столбца
	$query ="SHOW COLUMNS FROM " . $res_table;
	
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
		if($result)
		{
		    $row = mysqli_fetch_array($result);
		}

	
	$query1 ="DELETE FROM `".$res_table."` WHERE AccountId = ".$res_id." AND ServiceId = ".$res_serviceId." AND TariffId = ".$res_tariffId." AND Date='".$res_date ."'";
	
    //print_r($query1);
	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/index.php?table=$res_table");
?>