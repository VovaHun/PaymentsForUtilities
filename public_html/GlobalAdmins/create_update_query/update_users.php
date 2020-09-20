<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';

	$UserId                = $_POST[ 'UserId' ];
	$Login                 = $_POST[ 'Login' ];
	$Password              = $_POST[ 'Password' ];
	$Name                  = $_POST[ 'Name' ];
	$Gender                = ( is_null( $_POST[ 'Gender' ] ) ? 'NULL' : $_POST[ 'Gender' ] );
	$Email                 = ( is_null( $_POST[ 'Email' ] ) ? 'NULL' : $_POST[ 'Email' ] );
	$EmailNotifications    = ( ( isset( $_POST[ 'EmailNotifications' ] ) == true ) ? 1 : 0 );
	$Phone                 = ( is_null( $_POST[ 'Phone' ] ) ? 'NULL' : $_POST[ 'Phone' ] );
	$PhoneNotifications    = ( ( isset( $_POST[ 'PhoneNotifications' ] ) == true ) ? 1 : 0 );
	$AppealType            = ( is_null( $_POST[ 'AppealType' ] ) ? 'NULL' : $_POST[ 'AppealType' ] );
	$Appeal                = '';
	$Comment               = $_POST[ 'Comment' ];
	$ConsentOnPersonalData = ( ( isset( $_POST[ 'ConsentOnPersonalData' ] ) == true ) ? 1 : 0 );
	$BotId                 = ( is_null( $_POST[ 'BotId' ] ) ? 'NULL' : $_POST[ 'BotId' ] );
	$SocialId              = ( is_null( $_POST[ 'SocialId' ] ) ? 'NULL' : $_POST[ 'SocialId' ] );
	$SocialId              = ( empty( $SocialId ) ? '0' : $SocialId );
	$SocialNotifications   = ( ( isset( $_POST[ 'SocialNotifications' ] ) == true ) ? 1 : 0 );
	$RegistrationDate      = $_POST[ 'RegistrationDate' ];

	$fullname    = explode( ' ', $Name, 3 );
	$first_name  = ( ( count( $fullname ) >= 2 ) ? $fullname[ 1 ] : '' );
	$middle_name = ( ( count( $fullname ) >= 3 ) ? $fullname[ 2 ] : '' );
	$last_name   = ( ( count( $fullname ) >= 1 ) ? $fullname[ 0 ] : '' );
	
	if ( $AppealType == 1 ) {
		$Appeal = $first_name . " " . $last_name;
	}
	else if ( $AppealType == 2 ) {
		$Appeal = $first_name . " " . $middle_name;
	}
	else if ( $AppealType == 3 ) {
		$Appeal = ( ( $Gender == 2 ) ? 'г-жа' : 'г-н' ) . ' ' . $last_name;
	}
	
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

    if ( $UserId != -1 ) {
		$query = "UPDATE Users 
		          SET Login = '" . $Login . "', Password = '" . $Password . "', Name = '" . $Name . "', Gender = " . $Gender . ", Email = '" . $Email . "', EmailNotifications = " . $EmailNotifications . ", Phone = '" . $Phone . "', PhoneNotifications = " . $PhoneNotifications . ", AppealType = " . $AppealType . ", Appeal = '" . $Appeal . "', Comment = '" . $Comment . "', ConsentOnPersonalData = " . $ConsentOnPersonalData . ", BotId = " . $BotId . ", SocialId = " . $SocialId . ", SocialNotifications = " . $SocialNotifications . ", RegistrationDate = '" . $RegistrationDate . "' 
				  WHERE ( UserId = " . $UserId . " )";
	}
	else{	
		$query = "INSERT INTO Users ( Login, Password, Name, Gender, Email, EmailNotifications, Phone, PhoneNotifications, AppealType, Appeal, Comment, ConsentOnPersonalData, BotId, SocialId, SocialNotifications, RegistrationDate ) 
				  VALUES ( '" . $Login. "', '" . password_hash( $Password, PASSWORD_DEFAULT ) . "', '" . $Name . "', " . $Gender . ", '" . $Email . "', " . $EmailNotifications . ", '" . $Phone . "', " . $PhoneNotifications . ", " . $AppealType . ", '" . $Appeal . "', '" . $Comment . "', " . $ConsentOnPersonalData . ", " . $BotId . ", " . $SocialId . ", " . $SocialNotifications . ", '" . $RegistrationDate . "' )";
	}

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=Users" );
?>