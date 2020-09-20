<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	
	
	$name = $_POST['name'];
	$code = $_POST['code'];
	
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //print_r($_POST);	

	if($res_id == -1 ){
		$query1 = "INSERT INTO Regions (RegionСode,Name) VALUES ('$code','$name')";
	}
	else{
		$query1 = "UPDATE `Regions` SET `RegionСode`= '$code', `Name` = '$name' WHERE `Regions`.`RegionId` = '$res_id'";
	}
	
	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/?table=Regions");
?>