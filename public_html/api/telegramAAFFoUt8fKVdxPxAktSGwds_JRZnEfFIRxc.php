<?php
	ini_set( 'display_errors', 'Off' );
	include_once "apiTelegram.php";

	$bot    = new telegramBot( null, "847811843", "AAFFoUt8fKVdxPxAktSGwds_JRZnEfFIRxc" );
	$update = $bot->getLastUpdate();
	$result = $bot->main( $update );
	
	echo "<pre>"; 
	print_r( $update );
	echo "</pre>";

	if ( is_null( $result ) ) {
		echo "[" . $bot->lastErrorCode . "] " . $bot->lastErrorDescription;
		exit;
	}
	
	echo "<pre>"; 
	print_r( $result );
	echo "</pre>";

	$mysqli->close(); 
	ini_set( 'display_errors', 'On' );
?>