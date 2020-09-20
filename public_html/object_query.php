<?php
    //require $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
    //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    require $_SERVER['DOCUMENT_ROOT'].'/db_connect/sessionCheck.php';
    
    $companyid = $_POST["id"];
    
    $query = "SELECT Objects.ObjectId, Objects.Address 
              FROM Objects, ManagementCompany 
              WHERE Objects.CompanyId = ManagementCompany.CompanyId 
              AND ManagementCompany.CompanyId = $companyid  

              ORDER BY Objects.Address";
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
	
	$result_key = array();
	$result_value = array();
	$result_arr = array();
	
	while($row = mysqli_fetch_array($result)){
	    array_push($result_key, $row["ObjectId"]);
	    array_push($result_value, $row["Address"]);
	}
	
    array_push($result_arr, $result_key);
    array_push($result_arr, $result_value);
    
    echo json_encode($result_arr);
?>