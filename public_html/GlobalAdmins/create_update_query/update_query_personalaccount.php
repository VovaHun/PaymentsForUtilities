<?php
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_sessionCheck.php';
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/config.php';
	
	$name = $_POST['name'];
	$abonentId = $_POST['user'];
	$companyId = $_POST['companyId'];
	$objectId = $_POST['objectId'];

	$end = $_POST['end_date'];
	$used = ( ( isset( $_POST[ 'used' ] ) == true ) ? 1 : 0 );
	
	if ($_POST['start_date'] == NULL)
	{
	    $start =  date("Y-m-d");
	}
	else 
	{
	    $start = $_POST['start_date'];
	}

	$elem_id = $_POST['elem_id'];
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    if($elem_id == -1)
    {
    	$query1 = "INSERT INTO PersonalAccounts (Name, CompanyId, AbonentId,  ObjectId, StartDate, EndDate, `Using`) VALUES ('$name', '".$companyId."','".$abonentId."',  '".$objectId."', '$start', '$end', '$used')";
    }
    else
    {
    	$query1 = "UPDATE `PersonalAccounts` SET `Name` = '$name', `CompanyId` = '$companyId', `AbonentId` = '$abonentId', `ObjectId` = '$objectId', `StartDate` = '$start', `EndDate` = '$end', `Using` = '$used' WHERE `PersonalAccounts`.`AccountId` = ".$elem_id;
    }
	
	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
	
	header("Location: /GlobalAdmins/index.php?table=PersonalAccounts");
?>