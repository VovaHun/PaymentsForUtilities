<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';

	$prevUserId    = $_POST[ 'prevUserId' ];
	$prevAccountId = $_POST[ 'prevAccountId' ];
	$UserId        = $_POST[ 'UserId' ];
	$AccountId     = $_POST[ 'AccountId' ];
	$Active        = ( ( isset( $_POST[ 'Active' ] ) == true ) ? 1 : 0 );
	$Access        = ( ( isset( $_POST[ 'Access' ] ) == true ) ? 1 : 0 );
	
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

    if ( ( $prevUserId != -1 ) && ( $prevAccountId != -1 ) ) {
		$query = "UPDATE AccountUsers 
		          SET UserId = " . $UserId . ", AccountId = " . $AccountId . ", Active = " . $Active . ", Access = " . $Access . " 
				  WHERE ( UserId = " . $prevUserId . " ) AND
				        ( AccountId = " . $prevAccountId . " )";
	}
	else{	
		$query = "INSERT INTO AccountUsers ( UserId, AccountId, Active, Access ) 
				  VALUES ( " . $UserId. ", " . $AccountId . ", " . $Active . ", " . $Access . " )";
	}

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=AccountUsers" );
?>