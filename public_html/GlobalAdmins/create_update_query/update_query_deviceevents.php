<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_table = $_POST["elem_table"];
	$res_type =$_POST["elem_type"];
	$res_date =$_POST["elem_date"];
	
			/*$id = $_POST['id'];*/
			$device = $_POST['device'];
			$type = $_POST['type'];
			$type++;
			$date = $_POST['date'];
			$deviceIn = $_POST['deviceIn'];
			if($type==2||$type==3)
			{
			    $use=0;
			}
			else{
			    $use = 1 ;
			}
			


    
    //print_r($_POST);
	
  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($res_id == -1) {
		$query1 = "INSERT INTO DeviceEvents (DeviceId,EventType,`EventDate`,Indications,`Using`) VALUES (".$device.",".$type.",'$date',".$deviceIn.",".$use.")";
	}
	else{
		

		$query1 = "UPDATE DeviceEvents SET `DeviceId` ='$device',`EventType`='$type',EventDate='$date',`Indications` ='$deviceIn',`Using` ='$use' WHERE `DeviceId`='$res_id' AND  `EventType`='$res_type' AND EventDate='$res_date'";

	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location:  /GlobalAdmins/?table=".$res_table);


?>