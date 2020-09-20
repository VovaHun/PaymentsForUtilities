<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';

	$GlobalAdminId         = $_POST[ 'GlobalAdminId' ];
	$Login                 = $_POST[ 'Login' ];
	$Password              = $_POST[ 'Password' ];
	$Name                  = $_POST[ 'Name' ];
	$Email                 = $_POST[ 'Email' ];
	$Phone                 = $_POST[ 'Phone' ];
	$Comment               = $_POST[ 'Comment' ];
	
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

    if ( $GlobalAdminId != -1 ) 
	{
		$query = "UPDATE GlobalAdmins 
		          SET Login = '" . $Login . "', Password = '" . $Password . "', Name = '" . $Name . "', Email = '" . $Email . "', Phone = '" . $Phone . "', Comment = '" . $Comment . "' 
				  WHERE ( GlobalAdminId = " . $GlobalAdminId . " )";
	}
	else
	{	
		$query = "INSERT INTO GlobalAdmins ( Login, Password, Name, Email, Phone, Comment ) 
				  VALUES ( '" . $Login. "', '" . password_hash( $Password, PASSWORD_DEFAULT ) . "', '" . $Name . "', '" . $Email . "', '" . $Phone . "', '" . $Comment . "' )";
	}

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=GlobalAdmins" );
?>