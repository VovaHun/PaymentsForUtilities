<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';
	
    $elem_accid = $_POST["elem_accid"];
    $elem_date = $_POST["elem_date"];
    $elem_method = $_POST["elem_method"];

	$personalaccounts = $_POST["personalaccounts"];
	$method = $_POST["method"];
	$date = $_POST['date'];
	$payerfio = $_POST["payerfio"];
	$summa = $_POST['summa'];
  
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

	

    if ($elem_accid == -1 and $elem_date == -1 and $elem_method == -1) {
		$query1 = "INSERT INTO Payment (AccountId,`Date`,Method,PayerFio, Summa) VALUES (".$personalaccounts.",'$date',".$method.",'".$payerfio."',".$summa.")";
	}
	else{	
		$query1 = "UPDATE Payment SET `AccountId` ='$personalaccounts',`Date`='$date',`Method` ='$method',`PayerFio`='$payerfio',`Summa`='$summa' WHERE `AccountId`='$elem_accid' AND `Date`='$elem_date' AND `Method`='$elem_method'";
	}

	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

	header("Location:  /GlobalAdmins/?table=Payment");


?>