<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_table = $_POST["elem_table"];

	if($res_id == -1)
	{
			/*$id = $_POST['id'];*/
			$tarifftype = $_POST['tarifftype'];
			$service = $_POST['service'];
			$region = $_POST['region'];
			$date = $_POST['date'];
			$price = $_POST['price'];
			$company =$company_admin['CompanyId'];
			
	}
	else {
			/*$id = $_POST['id'];*/
			$tarifftype = $_POST['tarifftype'];
			$service = $_POST['service'];
			$region = $_POST['region'];
			$date = $_POST['date'];
			$price = $_POST['price'];
			$OldTarId = $_POST['OldTarId'];
			$OldSerId = $_POST['OldSerId'];
			$OldRegId = $_POST['OldRegId'];
			$company =$company_admin['CompanyId'];
	}

	if($_POST['price']==NULL){
	    $price=0;
	}
  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($res_id == -1) {
		$query1 = "INSERT INTO `Tariffs` (`TariffId`, `ServiceId`, `RegionId`,`CompanyId`, `Date`, `Price`) VALUES ('$tarifftype', '$service', '$region','$company' ,'$date', '$price')";
	}
	else{
		

		$query1 = "UPDATE Tariffs SET `TariffId` ='$tarifftype',`ServiceId`='$service',RegionId='$region',`CompanyId`='$company',`Date` ='$date',`Price` ='$price' WHERE `TariffId`='$OldTarId' AND `ServiceId`= '$OldSerId' AND  `RegionId`=  '$OldRegId' ";

	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location:  /ManagementCompany/?table=".$res_table);


?>