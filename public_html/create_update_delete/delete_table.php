<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["popup_id"];
	$res_table = $_POST["popup_table"];
	

	//print_r($res_table );
	//Запрос на название столбца
	$query ="SHOW COLUMNS FROM " . $res_table;
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

		if($result)
		{
		    $row = mysqli_fetch_array($result);
		}

	$query1 ="DELETE FROM `".$res_table."` WHERE `".$res_table."`.`".$row[0]."` = ".$res_id."";

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/index.php?table=$res_table");
?>