<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';
	
	//DeviceId=". $value['DeviceId']."&Date=" . $value['Date']

	$DeviceId   = $_GET[ 'DeviceId' ];
	$Date = $_GET[ 'Date' ];
	
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

	$query = "DELETE 
			  FROM DeviceIndications
			  WHERE ( DeviceId = " .$DeviceId . " ) AND
			        ( Date = '" . $Date . "' )";

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=DeviceIndications" );
?>