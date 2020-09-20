<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';

	$QueryId     = $_POST[ 'QueryId' ];
	$AccountName = $_POST[ 'AccountName' ];
	$UserId      = $_POST[ 'UserId' ];
	$CompanyId   = $_POST[ 'CompanyId' ];
	$ObjectId    = ( is_null( $_POST[ 'ObjectId' ] ) ? 'NULL' : $_POST[ 'ObjectId' ] );
	$QueryDate   = $_POST[ 'QueryDate' ];
	$QueryStatus = ( is_null( $_POST[ 'QueryStatus' ] ) ? 'NULL' : $_POST[ 'QueryStatus' ] );
	$QueryAnswer = ( is_null( $_POST[ 'QueryAnswer' ] ) ? 'NULL' : $_POST[ 'QueryAnswer' ] );

    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

    if ( $QueryId != -1 ) {
		$query = "UPDATE AccountsQuery 
		          SET AccountName = '" . $AccountName . "', UserId = " . $UserId . ", CompanyId = " . $CompanyId . ", ObjectId = " . $ObjectId . ", QueryDate = '" . $QueryDate . "' , QueryStatus = " . $QueryStatus . ", QueryAnswer = '" . $QueryAnswer . "' 
				  WHERE ( QueryId = " . $QueryId . " )";
	}
	else{
		$query = "INSERT INTO AccountsQuery ( AccountName, UserId, CompanyId, ObjectId, QueryDate, QueryStatus, QueryAnswer ) 
				  VALUES ( '" . $AccountName. "', " . $UserId . ", " . $CompanyId . ", " . $ObjectId . ", '" . $QueryDate . "', " . $QueryStatus . ", '" . $QueryAnswer . "' )";
	}

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=AccountsQuery" );
?>