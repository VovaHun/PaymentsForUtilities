<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_table = $_POST["elem_table"];

	if($res_id == -1)
	{
			/*$id = $_POST['id'];*/
			$deviceName = $_POST['deviceName'];
			$date = $_POST['date'];
			$deviceIn = $_POST['deviceIn'];
			$model = $_POST['model'];
			$dateNext = $_POST['dateNext'];
			$company = $_POST['Company'];
				/*print_r($deviceName);*/
			
	}
	else {
			/*$id = $_POST['id'];*/
			$deviceName = $_POST['deviceName'];
			$date = $_POST['date'];
			$deviceIn = $_POST['deviceIn'];
			$model = $_POST['model'];
			$dateNext = $_POST['dateNext'];
			$company = $_POST['Company'];
			/*	print_r($_POST);*/
	}


    
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($res_id == -1) {
		$query1 = "INSERT INTO Devices (Name,ReleaseDate,StartIndications,ModelId,NextDateCheck,CompanyId) VALUES   
		('$deviceName','$date','$deviceIn','$model','$dateNext', '$company')";
	}
	else{	

		$query1 = "UPDATE Devices SET Name ='$deviceName',`ReleaseDate`='$date ',`StartIndications` ='$deviceIn',
		`ModelId`='$model',`NextDateCheck`='$dateNext',`CompanyId`=  '$company' WHERE `DeviceId`='$res_id' ";

	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/?table=".$res_table);


?>