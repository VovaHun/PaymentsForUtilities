<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["popup_id"];
	$res_table = $_POST["popup_table"];
	$res_services = $_POST["services"];
	$res_regions = $_POST["regions"];
	

	//Запрос на название столбца
	$query ="SHOW COLUMNS FROM " . $res_table;
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

		if($result)
		{
		    $row = mysqli_fetch_array($result);
		}

	
	$query1 ="DELETE FROM `".$res_table."` WHERE TariffId = ".$res_id." AND ServiceId = ".$res_services." AND RegionId = ".$res_regions." ";

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/index.php?table=$res_table");
?>