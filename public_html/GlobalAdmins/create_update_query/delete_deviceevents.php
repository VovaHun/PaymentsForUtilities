<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';
	
	//DeviceId=". $value['DeviceId']."&Date=" . $value['Date']

	$DeviceId   = $_GET[ 'DeviceId' ];
	$EventType = $_GET[ 'EventType' ];
	$EventDate = $_GET[ 'EventDate' ];
	
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

	$query = "DELETE 
			  FROM DeviceEvents
			  WHERE ( DeviceId = " .$DeviceId . " ) AND
			        ( EventType = '" . $EventType . "')  AND
			        ( EventDate = '$EventDate')";

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=DeviceEvents" );
?>