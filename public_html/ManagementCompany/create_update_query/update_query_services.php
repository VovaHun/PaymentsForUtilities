<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_table = $_POST["elem_table"];

	
			
		$Name = $_POST['Name'];
		$contractor = $_POST['contractor'];
		$compose  = ( ( isset( $_POST['compose'] ) == true ) ? 1 : '0' );
		$company = $_POST['company'];
		$mainServ = $_POST['mainServ'];
		$unit = $_POST['unit'];
		$print  = ( ( isset( $_POST['print'] ) == true ) ? 1 : '0' );
			
	

    
  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($res_id == -1) {
		$query1 = "INSERT INTO `Services` ( `Name`, `СontractorId`, `IsComposite`, `MainServiceId`, `UnitId`, `IsPrint`, `CompanyId`) VALUES ('$Name', $contractor, $compose, $mainServ, '$unit', '$print', '".$company_admin['CompanyId']."')";
	}
	else{	

		$query1 = "UPDATE Services SET `Name` ='$Name',`СontractorId`='$contractor',`IsComposite` ='$compose',
		`MainServiceId`= $mainServ,`UnitId`='$unit', `IsPrint`= '$print', `CompanyId`='".$company_admin['CompanyId']."'  WHERE `ServiceId`='$res_id' ";

	}
	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location:  /ManagementCompany/?table=".$res_table);


?>