<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_table = $_POST["elem_table"];
	
	$name = $_POST['name'];
	
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    if($res_id == -1 ){
		$query1 = "INSERT INTO Units (Name) VALUES ('$name')";
	}
	else{
		$query1 = "UPDATE `Units` SET `Name` = '$name' WHERE `Units`.`UnitId` = '$res_id'";
	}
	

	
	
	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/?table=Units");
?>