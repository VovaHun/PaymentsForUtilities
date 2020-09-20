<?php
    //require $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
    //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    require $_SERVER['DOCUMENT_ROOT'].'/db_connect/sessionCheck.php';
    
    $regionid = $_POST["id"];
    
    $query = "SELECT ManagementCompany.CompanyId, ManagementCompany.Name 
              FROM ManagementCompany 
              WHERE ManagementCompany.RegionId = ".$regionid.
              " ORDER BY ManagementCompany.Name";
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
	
	$result_key = array();
	$result_value = array();
	$result_arr = array();
	
	while($row = mysqli_fetch_array($result)){
	    array_push($result_key, $row["CompanyId"]);
	    array_push($result_value, $row["Name"]);
	}
	
	//for ($i = 0; $i<count($result_key); $i++) {
    //    $result_arr[$result_key[$i]] = $result_value[$i];
    //}
    array_push($result_arr, $result_key);
    array_push($result_arr, $result_value);
    
    echo json_encode($result_arr);
?>