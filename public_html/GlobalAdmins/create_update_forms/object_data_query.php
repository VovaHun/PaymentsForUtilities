<?php
    //require $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
    //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';
    
    $objectid = $_POST["id"];
    
    $query = "SELECT Objects.Name, Objects.ObjectType, Objects.Square 
              FROM Objects 
              WHERE Objects.ObjectId = $objectid  
              GROUP BY Objects.Address
              ORDER BY Objects.Address";
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
	
	$result_arr = array();
	
	while($row = mysqli_fetch_array($result)){
	    $result_name = $row["Name"];
	    $result_type = $row["ObjectType"];
	    $result_square = $row["Square"];
	}
	
    array_push($result_arr, $result_name);
    array_push($result_arr, $result_type);
    array_push($result_arr, $result_square);
    
    echo json_encode($result_arr);
?>