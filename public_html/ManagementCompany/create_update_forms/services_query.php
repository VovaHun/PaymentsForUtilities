<?php
  //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_sessionCheck.php';
  
  $ServiceId = $_POST["ServiceId"];
  $ContractorId = $_POST["ContractorId"];
  
  $query= "SELECT Services.ServiceId, Services.Name FROM Services WHERE Services.ServiceId != ".$ServiceId." AND Services.СontractorId = ".$ContractorId." AND Services.CompanyId = ".$company_admin['CompanyId']." ORDER BY Services.Name";
  $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
	
	//print_r($query);
	
	$result_key = array();
	$result_value = array();
	$result_arr = array();
	
	while($row = mysqli_fetch_array($result)){
    array_push($result_key, $row["ServiceId"]);
    array_push($result_value, $row["Name"]);
	}
	
    array_push($result_arr, $result_key);
    array_push($result_arr, $result_value);
    
    echo json_encode($result_arr);
?>