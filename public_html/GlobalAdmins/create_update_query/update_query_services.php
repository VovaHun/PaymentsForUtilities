<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	

			/*$id = $_POST['id'];*/
			$Name = $_POST['Name'];
			$contractor = $_POST['contractor'];
			$compose  = ( ( isset( $_POST['compose'] ) == true ) ? 1 : '0' );
			//$compose = $_POST['compose'];
			$company = $_POST['company'];
			$mainServ = $_POST['mainServ'];
			$unit = $_POST['unit'];
			$print  = ( ( isset( $_POST['print'] ) == true ) ? 1 : '0' );
			
		/*	if(!isset($_POST['print'])){
			    $print = 0;
			}
			else{
			   $print = $_POST['print'];
			}*/
	

    //print_r($_POST);
  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($res_id == -1) {
		$query1 = "INSERT INTO `Services` ( `Name`, `СontractorId`,`CompanyId` ,`IsComposite` ,`MainServiceId`, `UnitId`, `IsPrint`) VALUES ('$Name', $contractor,$company ,$compose, $mainServ, '$unit', '$print')";
	}
	else{	

		$query1 = "UPDATE Services SET `Name` ='$Name',`СontractorId`='$contractor',`CompanyId`='$company',`IsComposite` ='$compose',
		`MainServiceId`= $mainServ,`UnitId`='$unit', `IsPrint`= '$print' WHERE `ServiceId`='$res_id' ";

	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location:  /GlobalAdmins/?table=Services");


?>