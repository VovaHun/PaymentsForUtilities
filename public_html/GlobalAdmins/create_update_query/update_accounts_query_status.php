<?php
	require $_SERVER[ 'DOCUMENT_ROOT' ] . '/GlobalAdmins/_sessionCheck.php';
	include_once $_SERVER[ 'DOCUMENT_ROOT' ] . "/api/apiCommon.php";
	include_once $_SERVER[ 'DOCUMENT_ROOT' ] . "/api/apiTelegram.php";

	$QueryId     = $_POST[ 'QueryId' ];
	$QueryStatus = ( is_null( $_POST[ 'QueryStatus' ] ) ? '0' : $_POST[ 'QueryStatus' ] );
	$QueryAnswer = ( is_null( $_POST[ 'QueryAnswer' ] ) ? '' : $_POST[ 'QueryAnswer' ] );

    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

	$query = "UPDATE AccountsQuery 
			  SET QueryStatus = " . $QueryStatus . ", QueryAnswer = '" . $QueryAnswer . "' 
			  WHERE ( QueryId = " . $QueryId . " )";

	$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );
	
	if ( $result !== false ) { 
		if ( $QueryStatus == 1 ) {
			$update = false;
			$query  = "SELECT 
						   AccountUsers.UserId AS UserId 
					   FROM AccountUsers AS AccountUsers 
					   WHERE ( AccountUsers.UserId = " . $_POST[ 'UserId' ] . " ) AND 
							 ( AccountUsers.AccountId = " . $_POST[ 'AccountId' ] . " ) 
					   LIMIT 1";

			if ( $result = mysqli_query( $mysqli, $query ) ) { 
				if ( $row = mysqli_fetch_array( $result ) ) { 
					$update = true;
				} 
			}
			
			if ( !$update ) {
				$query  = "INSERT INTO AccountUsers ( UserId, AccountId, Active, Access ) 
						   VALUES ( " . $_POST[ 'UserId' ]. ", " . $_POST[ 'AccountId' ] . ", 1, " . ( ( isset( $_POST[ 'Access' ] ) == true ) ? 1 : 0 ) . " )";
			}
			else {
				$query = "UPDATE AccountUsers 
						  SET Active = 1, Access = " . ( ( isset( $_POST[ 'Access' ] ) == true ) ? 1 : 0 ) . " 
						  WHERE ( UserId = " . $_POST[ 'UserId' ] . " ) AND 
								( AccountId = " . $_POST[ 'AccountId' ] . " )";
			}

			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );
		}
		else {
			$result = true;
		}

		if ( $result !== false ) {
			$user = commonGetUserInfo( $_POST[ 'UserId' ] );
			
			if ( $user !== false ) {
				if ( ( $user[ 'BotType' ] == 1 ) && ( $user[ 'BotSocialId' ] != 0 ) && ( $user[ 'SocialNotifications' ] == 1 ) ) {
					if ( $QueryStatus == 1 ) {
						$text = 'Ваш запрос о добавлении лицевого счета (' . $_POST[ 'AccountName' ] . ') одобрен.';
					}
					else {
						$text = 'В регистрации лицевого счета (' . $_POST[ 'AccountName' ] . ') отказано.' . chr( 10 ) . $QueryAnswer;
					}

					$bot  = new telegramBot( $user[ 'BotId' ], $user[ 'BotSocialId' ], $user[ 'BotToken' ] );  
					return $bot->sendMessage( $user[ 'SocialId' ], $text );
				}
			}
		}
	}

	header( "Location: ../index.php?table=AccountsQuery" );
?>