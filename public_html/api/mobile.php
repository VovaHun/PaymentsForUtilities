<?php
	header( 'Content-Type: application/json' );
	ini_set( 'display_errors', 'Off' );
	include_once "apiMobile.php";
	
	if ( isset( $_GET[ 'method' ] ) ) {
		$method    = $_GET[ 'method' ];
		$id        = $_GET[ 'id' ];
		$arguments = $_GET;
	}
		
	if ( isset( $_POST[ 'method' ] ) ) {
		$method    = $_POST[ 'method' ];
		$id        = $_POST[ 'id' ];
		$arguments = $_POST;
	}
	
	$mobile = new mobileUser( $id );
	echo $mobile->call( $method, $arguments );

	$mysqli->close(); 
	ini_set( 'display_errors', 'On' );
?>
