<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';

	$user_id    = $_GET[ 'UserId' ];
	$account_id = $_GET[ 'AccountId' ];
	
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

	$query = "DELETE 
			  FROM AccountUsers
			  WHERE ( UserId = " . $user_id . " ) AND
			        ( AccountId = " . $account_id . " )";

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=AccountUsers" );
?>