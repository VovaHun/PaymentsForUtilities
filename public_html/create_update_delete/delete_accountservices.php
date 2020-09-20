<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["servAccountId"];
	$res_table = $_POST["popup_table"];
	$res_serviceId = $_POST["serviceId"];
	$res_date = $_POST["dateId"];
	
	if ($_POST["tariffId"] != ""){
	    $res_tariffId = $_POST["tariffId"];
	    
	    $query1 ="DELETE FROM `".$res_table."` WHERE AccountId = ".$res_id." AND ServiceId = ".$res_serviceId." AND TariffId = ".$res_tariffId." AND Date ='".$res_date."'";
	}
	else {
	    $query1 ="DELETE FROM `".$res_table."` WHERE AccountId = ".$res_id." AND ServiceId = ".$res_serviceId." AND Date ='".$res_date."' AND TariffId IS NULL ";
	}
   // print_r($query1);
	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/index.php?table=$res_table");
?>