<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["modelId"];
	$res_table = $_POST["popup_table"];
	$res_tariffId = $_POST["tariffId"];
	//print_r($_POST);
	

	//Запрос на название столбца
	$query ="SHOW COLUMNS FROM " . $res_table;
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

		if($result)
		{
		    $row = mysqli_fetch_array($result);
		}

	
	$query1 ="DELETE FROM `".$res_table."` WHERE  ModelId = ".$res_id." AND TariffId = ".$res_tariffId." ";

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/index.php?table=$res_table");
?>