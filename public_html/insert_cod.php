<?php 
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/sessionCheck.php'; 

	$DeviceId = $_POST['DeviceId'];
	$Date = $_POST['Date'];
	$Indications = $_POST['Indications'];
	$persid = $_POST['personalId'];
    //print_r($persid);
	$DateNow =  date("Y-m-d");

	$queryDate = "SELECT DeviceId, Date FROM DeviceIndications 
	WHERE DeviceId = '$DeviceId'
	AND Date='$DateNow'";

	$resDate = mysqli_query($link, $queryDate) or die("Ошибка " . mysqli_error($link));
	$row = mysqli_fetch_array($resDate);

	if(!$row)
	{
		$query2 = "INSERT INTO `DeviceIndications` ( `DeviceId`, `Date`, `Indications`) 
		VALUES ('$DeviceId', '$DateNow', $Indications) ";	    
	}
	else
	{
		$query2 = "UPDATE `DeviceIndications` SET `Indications` = '$Indications' WHERE `DeviceIndications`.`DeviceId` = '$DeviceId'";
	}


	$resultIn = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link));

	
	header("Location: /index.php?table=IncludeTable&persid=".$persid."&end=1&div=".$DeviceId);
?>