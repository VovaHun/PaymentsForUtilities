<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';

	$BotId    = $_POST[ 'BotId' ];
	$BotType  = ( is_null( $_POST[ 'BotType' ] ) ? 0 : $_POST[ 'BotType' ] );
	$SocialId = ( is_null( $_POST[ 'SocialId' ] ) ? 0 : $_POST[ 'SocialId' ] );
	$Name     = $_POST[ 'Name' ];
	$Token    = $_POST[ 'Token' ];
	
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

    if ( $BotId != -1 ) {
		$query = "UPDATE Bots 
		          SET BotType = " . $BotType . ", SocialId = " . $SocialId . ", Name = '" . $Name . "', Token = '" . $Token . "' 
				  WHERE ( BotId = " . $BotId . " )";
	}
	else{	
		$query = "INSERT INTO Bots ( BotType, SocialId, Name, Token ) 
				  VALUES ( " . $BotType . ", " . $SocialId . ", '" . $Name . "', '" . $Token . "' )";
	}

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=Bots" );
?>