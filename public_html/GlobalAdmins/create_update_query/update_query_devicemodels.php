<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_table = $_POST["elem_table"];

	if($res_id == -1)
	{
			/*$id = $_POST['id'];*/
			$deviceName = $_POST['deviceName'];
			$dateInt = $_POST['dateInt'];
			$creator = $_POST['creator'];
				/*print_r($deviceName);*/
			
	}
	else {
			/*$id = $_POST['id'];*/
			$deviceName = $_POST['deviceName'];
			$dateInt = $_POST['dateInt'];
			$creator = $_POST['creator'];
			/*	print_r($_POST);*/
	}


  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($res_id == -1) {
		$query1 = "INSERT INTO DeviceModels (Name,CheckInterval,Maker) VALUES ('$deviceName',".$dateInt.",'$creator')";
	}
	else{	

		$query1 = "UPDATE DeviceModels SET Name ='$deviceName',`CheckInterval`='$dateInt',`Maker` ='$creator' WHERE `ModelId`='$res_id' ";

	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/?table=".$res_table);


?>