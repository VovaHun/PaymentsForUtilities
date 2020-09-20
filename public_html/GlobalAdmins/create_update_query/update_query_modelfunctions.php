<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_table = $_POST["elem_table"];
	$res_tariff = $_POST["elem_tariff"];
	
 	
	//print_r($_POST);

	$name = array();
	$value = array();
	$edit_ar = array();

	
	// вывод названий полей
	$query ="SHOW COLUMNS FROM " . $res_table;
	
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

	//массив названий полей
	if($result)
	{
	    while($row = mysqli_fetch_array($result)){
	    	$row["0"] = "`".$row["0"]."`";

			array_push($name, $row["0"]);
		}
	}

    $_POST["fn"][2]  = ( ( isset( $_POST["fn"][2] ) == true ) ? 1 : '0' );
	//Массив значений полей
	for ($i = 0; $i < count($_POST["fn"]); $i++) {
		if ($_POST["fn"][$i] != 'NULL' and $_POST["fn"][$i] != '') {
			$_POST["fn"][$i] = "'".$_POST["fn"][$i]."'";
		}
		elseif ($_POST["fn"][$i] == '') {
			$_POST["fn"][$i] = 'NULL';
		}
		array_push($value, $_POST["fn"][$i]);
	}
	
    
   
    
	//Массив для запроса на добавление
	for ($j = 0; $j < count($value); $j++) {
		array_push($edit_ar, $name[$j]. " = " .$value[$j]);
	}

	//Массивы переведены в строки и разделены запятыми
	$name_imp = implode(", ", $name);
	$value_imp = implode(", ", $value);
	$edit_ar_imp = implode(", ", $edit_ar);


	//print_r($edit_ar_imp );

	//Запросы на добавление и редактирование
	if ($res_id == -1) {
		$query1 = "INSERT INTO `".$res_table. "` (" .$name_imp. ") VALUES (" .$value_imp. ")";
	}
	else{
		$query1 = "UPDATE `".$res_table."` SET " .$edit_ar_imp. " WHERE `".$res_table."`.".$name[0]." = ".$res_id." 
		AND `".$res_table."`.".$name[1]." = ".	$res_tariff;
	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/?table=$res_table");

?>


