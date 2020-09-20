<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_date = $_POST["elem_date"];
	$res_table = $_POST["elem_table"];
	
			

	$device = $_POST['device'];
	$date = $_POST['date'];
	$deviceIn = $_POST['deviceIn'];
	
	
	$fixed   = ( ( isset( $_POST['fixed'] ) == true ) ? 1 : 0 );
  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($res_id == -1 & $res_date ==-1) {
		$query1 = "INSERT INTO DeviceIndications (DeviceId,`Date`,Indications,Fixed) VALUES (".$device.",'$date',".$deviceIn.",".$fixed.")";
	}
	else{	

		$query1 = "UPDATE DeviceIndications SET `DeviceId` ='$device',`Date`='$date',`Indications` ='$deviceIn',`Fixed`='$fixed' WHERE `DeviceId`='$res_id' AND `Date`='$res_date'";

	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location:  /GlobalAdmins/?table=".$res_table);


?>