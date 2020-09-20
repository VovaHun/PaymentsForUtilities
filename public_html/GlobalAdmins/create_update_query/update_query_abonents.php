<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';

	$res_id = $_POST["elem_id"];
	$res_table = $_POST["elem_table"];
	
	//print_r($_POST);

			/*$id = $_POST['id'];*/
			$type = $_POST['type'];
			$Name = $_POST['Name'];
			$adres = $_POST['adres'];
			$adres2 = $_POST['adres2'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$inn = $_POST['inn'];
			$kpp = $_POST['kpp'];
			$orgn = $_POST['orgn'];
			$datebith = $_POST['datebith'];
			$head = $_POST['head'];
			$headName = $_POST['headName'];
			$payAc = $_POST['payAc'];
			$bank = $_POST['bank'];
			$bik = $_POST['bik'];
			$corAc = $_POST['corAc'];
			if(!isset($_POST['consent'])){
			    $consent = 0;
			}
			else{
			    $consent = $_POST['consent'];
			}
			
				/*print_r($deviceName);*/
			
				
			

	


  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($res_id == -1) {
        if(empty($datebith)){
            	$query1 = "INSERT INTO `Abonents` ( `AbonentType`, `Name`, `LegalAddress`, `ActualAddress`, `Email`, `Phone`, `INN`, `KPP`, `OGRN`, `DateOfBirth`, `PositionHead`, `FIO`, `PaymentAccount`, `Bank`, `BIK`, `CorrespondentAccount`, `ConcentOnPersonalData`) VALUES ( '$type', '$Name', '$adres', '$adres2', '$email', '$phone', '$inn', '$kpp','$orgn', NULL, '$head', '$headName', '$payAc','$bank', '$bik', '$corAc', '$consent')";
        }
        else{
            	$query1 = "INSERT INTO `Abonents` ( `AbonentType`, `Name`, `LegalAddress`, `ActualAddress`, `Email`, `Phone`, `INN`, `KPP`, `OGRN`, `DateOfBirth`, `PositionHead`, `FIO`, `PaymentAccount`, `Bank`, `BIK`, `CorrespondentAccount`, `ConcentOnPersonalData`) VALUES ( '$type', '$Name', '$adres', '$adres2', '$email', '$phone', '$inn', '$kpp','$orgn', '$datebith', '$head', '$headName', '$payAc','$bank', '$bik', '$corAc', '$consent')";
        }
            
	}
	else{	
	        $query1 = "UPDATE `Abonents` SET `AbonentType` = '$type', `Name` = '$Name', `LegalAddress` = '$adres', `ActualAddress` = '$adres2', `Email` = '$email', `Phone` = '$phone', `INN` = '$inn', `KPP` = '$kpp', `OGRN` = '$orgn', `DateOfBirth` = '$datebith', `PositionHead` = '$head', `FIO` = '$headName', `PaymentAccount` = '$payAc', `Bank` = '$bank', `BIK` = '$bik', `CorrespondentAccount` = '$corAc', `ConcentOnPersonalData` = '$consent' WHERE `Abonents`.`AbonentId` = '$res_id' ";

	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location: /GlobalAdmins/?table=".$res_table);


?>