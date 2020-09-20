<?php
	include_once $_SERVER[ 'DOCUMENT_ROOT' ] . "/api/apiTelegram.php";

    $bot    = new telegramBot( $_GET[ 'BotId' ], $_GET[ 'SocialId' ], $_GET[ 'Token' ] );  
	$result = $bot->deleteWebhook();
	
	if ( is_null( $result ) ) {
		echo "[" . $bot->lastErrorCode . "] " . $bot->lastErrorDescription;
		exit;
	}
	
	header( "Refresh: 0; url='../index.php?table=Bots'" );
?>
