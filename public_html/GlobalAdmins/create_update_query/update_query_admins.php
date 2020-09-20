<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';

	$AdminId               = $_POST[ 'AdminId' ];
	$Login                 = $_POST[ 'Login' ];
	$Password              = $_POST[ 'Password' ];
	$CompanyId             = $_POST[ 'CompanyId' ];
	$Name                  = $_POST[ 'Name' ];
	$Email                 = $_POST[ 'Email' ];
	$Phone                 = $_POST[ 'Phone' ];
	$Comment               = $_POST[ 'Comment' ];
	
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

    if ( $AdminId != -1 ) 
	{
		$query = "UPDATE Admins 
		          SET Login = '" . $Login . "', Password = '" . $Password . "', CompanyId = " . $CompanyId . ", Name = '" . $Name . "', Email = '" . $Email . "', Phone = '" . $Phone . "', Comment = '" . $Comment . "' 
				  WHERE ( AdminId = " . $AdminId . " )";
	}
	else
	{	
		$query = "INSERT INTO Admins ( Login, Password, CompanyId, Name, Email, Phone, Comment ) 
				  VALUES ( '" . $Login. "', '" . password_hash( $Password, PASSWORD_DEFAULT ) . "', " . $CompanyId . ", '" . $Name . "', '" . $Email . "', '" . $Phone . "', '" . $Comment . "' )";
	}

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );

	header( "Location: ../index.php?table=Admins" );
?>