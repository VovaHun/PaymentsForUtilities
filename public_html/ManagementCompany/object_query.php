<?php
  //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_sessionCheck.php';
  
  $ObjectType = $_POST["ObjectType"];
  $Elem_id = $_POST["Elem_id"];

  $ObjectTypeNew = intval($ObjectType) - 1;
  
  $query = "SELECT Objects.ObjectId, Objects.KadastrNo 
            FROM Objects
            WHERE Objects.ObjectType = ".$ObjectTypeNew."  
            AND Objects.ObjectId != ".$Elem_id." 
            AND Objects.CompanyId = ".$company_admin['CompanyId']." 
            GROUP BY Objects.ObjectId
            ORDER BY Objects.KadastrNo";
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
	
	$result_key = array();
	$result_value = array();
	$result_arr = array();
	
	while($row = mysqli_fetch_array($result)){
    array_push($result_key, $row["ObjectId"]);
    array_push($result_value, $row["KadastrNo"]);
	}
	
    array_push($result_arr, $result_key);
    array_push($result_arr, $result_value);
    
    echo json_encode($result_arr);
?>