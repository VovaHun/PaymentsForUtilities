<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["DevAccountId"];
	$res_table = $_POST["popup_table"];
	$res_serviceId = $_POST["serviceId"];
	$res_tariffId = $_POST["tariffId"];
	$res_deviceId = $_POST["deviceId"];
	$res_data = $_POST["dateId"];
	

	//Запрос на название столбца
	$query ="SHOW COLUMNS FROM " . $res_table;
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

		if($result)
		{
		    $row = mysqli_fetch_array($result);
		}

	
	$query1 ="DELETE FROM `".$res_table."` WHERE AccountId = ".$res_id." AND ServiceId = ".$res_serviceId." AND TariffId = ".$res_tariffId." AND DeviceId=".$res_deviceId." AND Date='".$res_data."'";

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/index.php?table=$res_table");
?>