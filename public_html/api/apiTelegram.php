<?php
	//header('Content-Type: text/html; charset=utf-8');
	include_once $_SERVER[ 'DOCUMENT_ROOT' ] . "/api/_connection.php";
	include_once $_SERVER[ 'DOCUMENT_ROOT' ] . "/api/apiCommon.php";
	

	if ( !function_exists( 'json_last_error_msg' ) ) {
        function json_last_error_msg() {
            static $ERRORS = array(
                JSON_ERROR_NONE            		 => 'Ошибок нет',
				JSON_ERROR_DEPTH		   	 	 => 'Достигнута максимальная глубина стека',
				JSON_ERROR_STATE_MISMATCH		 => 'Неверный или некорректный JSON',
				JSON_ERROR_CTRL_CHAR			 => 'Ошибка управляющего символа, возможно неверная кодировка',
				JSON_ERROR_SYNTAX		   		 => 'Синтаксическая ошибка',
				JSON_ERROR_UTF8		       	 	 => 'Некорректные символы UTF-8, возможно неверная кодировка',
				JSON_ERROR_RECURSION			 => 'Одна или несколько зацикленных ссылок в кодируемом значении',
				JSON_ERROR_INF_OR_NAN			 => 'Одно или несколько значений NAN или INF в кодируемом значении',
				JSON_ERROR_UNSUPPORTED_TYPE		 => 'Передано значение с неподдерживаемым типом',
				JSON_ERROR_INVALID_PROPERTY_NAME => 'Имя свойства не может быть закодировано',
				JSON_ERROR_UTF16				 => 'Некорректный символ UTF-16, возможно некорректно закодирован'
            );

            $error = json_last_error();
            return isset( $ERRORS[ $error ] ) ? $ERRORS[ $error ] : 'Unknown error';
        }
    }
	
	
	function mb_word( $str, $encoding ) {
		$str   = mb_strtolower( $str, $encoding );
		$first = mb_substr( $str, 0, 1, $encoding );
		$last  = mb_substr( $str, 1, mb_strlen( $str, $encoding ), $encoding );
		
		return trim( mb_strtoupper( $first, $encoding ) . $last );
	}
	
	
	function html_replace_special( $value ) {
		$value = str_replace( '<', '«',  $value );
		$value = str_replace( '>', '»',  $value );
		$value = str_replace( '&', ' ', $value );
		
		return $value;
	}


	class telegramUser {
		public $bot_id                   = null;
		public $id                       = null; 
		public $is_bot                   = false;
		public $username                 = '';
		public $full_name                = '';
		public $first_name               = '';
		public $middle_name              = '';
		public $last_name                = '';
		public $gender                   = 0;
		public $email                    = '';
		public $email_notifications      = 0;
		public $phone                    = '';
		public $phone_notifications      = 0;
		public $appeal_type              = 0;
		public $appeal                   = '';
		public $comment                  = '';
		public $consent_on_personal_data = 0;
		public $social_notifications     = 0;
		public $registered               = false;
		public $query                    = false;
		public $user_id                  = null;
		

		public function __construct( $bot_id, $from, $chat = null ) {
			if ( is_null( $chat ) ) {
				unset( $chat );
			}
			
			$this->bot_id     = $bot_id;
			$this->id         = ( isset( $from ) ? $from->id : $chat->id );
			$this->is_bot     = ( isset( $from->is_bot ) ? $from->is_bot : false );
			$this->username   = trim( isset( $from->username ) ? $from->username : ( isset( $chat->username ) ? $chat->username : '' ) );
			$this->first_name = trim( isset( $from->first_name ) ? $from->first_name : ( isset( $chat->first_name ) ? $chat->first_name : '' ) );
			$this->last_name  = trim( isset( $from->last_name ) ? $from->last_name : ( isset( $chat->last_name ) ? $chat->last_name : '' ) );
			$this->full_name  = $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name;
			
			if ( ( $this->appeal_type == 1 ) && !empty( $this->first_name ) && !empty( $this->last_name ) ) {
				$this->appeal = $this->first_name . ' ' . $this->last_name;
			}
			else if ( ( $this->appeal_type == 2 ) && !empty( $this->first_name ) && !empty( $this->middle_name ) ) {
				$this->appeal = $this->first_name . ' ' . $this->middle_name;
			}
			else if ( ( $this->appeal_type == 3 ) && !empty( $this->last_name ) ) {
				$this->appeal = ( ( $this->gender == 2 ) ? 'г-жа' : 'г-н' ) . ' ' . $this->last_name;
			}
			else if ( !empty( $this->first_name ) && !empty( $this->last_name ) ) {
				$this->appeal = $this->first_name . ' ' . $this->last_name;
			}	
			else if ( !empty( $this->first_name ) ) {
				$this->appeal = $this->first_name;
			}	
			else if ( !empty( $this->username ) ) {
				$this->appeal = $this->username;
			}	
			else if ( empty( $this->appeal ) ) {
				$this->appeal = 'Гость';
			}
		}
		
		
		public function getUserInfo() {
			global $mysqli;
			
			if ( is_null( $this->bot_id ) ) {
				return;
			}
			
			$query = "SELECT * 
					  FROM Users
					  WHERE ( BotId = " . $this->bot_id . " ) AND 
					        ( SocialId = " . $this->id . " ) 
					  LIMIT 1";
			
			if ( $result = mysqli_query( $mysqli, $query ) ) { 
				if ( $row = mysqli_fetch_array( $result ) ) { 
					$this->full_name                = trim( $row[ 'Name' ] );
					$this->gender                   = $row[ 'Gender' ];
					$this->email                    = trim( $row[ 'Email' ] );
					$this->email_notifications      = $row[ 'EmailNotifications' ];
					$this->phone                    = trim( $row[ 'Phone' ] );
					$this->phone_notifications      = $row[ 'PhoneNotifications' ];
					$this->appeal_type              = $row[ 'AppealType' ];
					$this->appeal                   = trim( $row[ 'Appeal' ] );
					$this->comment                  = trim( $row[ 'Comment' ] );
					$this->consent_on_personal_data = $row[ 'ConsentOnPersonalData' ];
					$this->social_notifications     = $row[ 'SocialNotifications' ];
					$this->registered               = true;
					$this->query                    = false;
					$this->user_id                  = $row[ 'UserId' ];
					
					$fullname          = explode( ' ', $this->full_name, 3 );
					$this->first_name  = ( ( count( $fullname ) >= 2 ) ? mb_word( $fullname[ 1 ], 'UTF-8' ) : $this->first_name );
					$this->middle_name = ( ( count( $fullname ) >= 3 ) ? mb_word( $fullname[ 2 ], 'UTF-8' ) : $this->middle_name );
					$this->last_name   = ( ( count( $fullname ) >= 1 ) ? mb_word( $fullname[ 0 ], 'UTF-8' ) : $this->last_name );
				} 

				$result->close(); 
			} 
			
			if ( !$this->registered ) {
				$query = "SELECT * 
						  FROM UsersQuery
						  WHERE ( BotId = " . $this->bot_id . " ) AND 
								( SocialId = " . $this->id . " ) 
						  LIMIT 1";

				if ( $result = mysqli_query( $mysqli, $query ) ) { 
					while( $row = mysqli_fetch_array( $result ) ){ 
						$this->full_name                = trim( $row[ 'Name' ] );
						$this->gender                   = $row[ 'Gender' ];
						$this->email                    = trim( $row[ 'Email' ] );
						$this->email_notifications      = $row[ 'EmailNotifications' ];
						$this->phone                    = trim( $row[ 'Phone' ] );
						$this->phone_notifications      = $row[ 'PhoneNotifications' ];
						$this->appeal_type              = $row[ 'AppealType' ];
						$this->appeal                   = trim( $row[ 'Appeal' ] );
						$this->comment                  = trim( $row[ 'Comment' ] );
						$this->consent_on_personal_data = $row[ 'ConsentOnPersonalData' ];
						$this->social_notifications     = $row[ 'SocialNotifications' ];
						$this->registered               = false;
						$this->query                    = true;
						$this->user_id                  = null;
						
						$fullname          = explode( ' ', $this->full_name, 3 );
						$this->first_name  = ( ( count( $fullname ) >= 2 ) ? mb_word( $fullname[ 1 ], 'UTF-8' ) : $this->first_name );
						$this->middle_name = ( ( count( $fullname ) >= 3 ) ? mb_word( $fullname[ 2 ], 'UTF-8' ) : $this->middle_name );
						$this->last_name   = ( ( count( $fullname ) >= 1 ) ? mb_word( $fullname[ 0 ], 'UTF-8' ) : $this->last_name );
					} 

					$result->close(); 
				}
			}

			if ( ( $this->appeal_type == 1 ) && !empty( $this->first_name ) && !empty( $this->last_name ) ) {
				$this->appeal = $this->first_name . ' ' . $this->last_name;
			}
			else if ( ( $this->appeal_type == 2 ) && !empty( $this->first_name ) && !empty( $this->middle_name ) ) {
				$this->appeal = $this->first_name . ' ' . $this->middle_name;
			}
			else if ( ( $this->appeal_type == 3 ) && !empty( $this->last_name ) ) {
				$this->appeal = ( ( $this->gender == 2 ) ? 'г-жа' : 'г-н' ) . ' ' . $this->last_name;
			}
			else if ( !empty( $this->first_name ) && !empty( $this->last_name ) ) {
				$this->appeal = $this->first_name . ' ' . $this->last_name;
			}	
			else if ( !empty( $this->first_name ) ) {
				$this->appeal = $this->first_name;
			}	
			else if ( !empty( $this->username ) ) {
				$this->appeal = $this->username;
			}	
			else if ( empty( $this->appeal ) ) {
				$this->appeal = 'Гость';
			}
		}
		
		
		public function deleteUsersQuery() {
			global $mysqli;
			
			if ( is_null( $this->bot_id ) ) {
				return false;
			}
			
			$query = "DELETE 
					  FROM UsersQuery 
					  WHERE ( BotId = " . $this->bot_id . " ) AND 
					        ( SocialId = " . $this->id . " )";

			return mysqli_query( $mysqli, $query );
		}
		
		
		public function updateUsersQuery( $field, $value ) {
			global $mysqli;
			
			if ( is_null( $this->bot_id ) ) {
				return false;
			}
			
			if ( !$this->query ) {
				$query = "INSERT INTO UsersQuery ( Login, Password, Name, Gender, Email, EmailNotifications, Phone, PhoneNotifications, AppealType, Appeal, Comment, ConsentOnPersonalData, BotId, SocialId, SocialNotifications, QueryDate ) 
						  VALUES ( '" . ( empty( $this->username ) ? $this->id : $this->username ) . "', '', '" . $this->full_name . "', " . $this->gender . ", '" . $this->email . "', " . $this->email_notifications . ", '" . $this->phone . "', " . $this->phone_notifications . ", " . $this->appeal_type . ", '" . $this->appeal . "', '" . $this->comment . "', " . $this->consent_on_personal_data . ", " . $this->bot_id . ", " . $this->id . ", " . $this->social_notifications . ", NOW() )";

				if ( mysqli_query( $mysqli, $query ) === false ) {
					return false;
				}
			}

			$query = "UPDATE UsersQuery 
					  SET QueryDate = NOW(), ";
						
			if ( ( $field == 'Gender' ) || ( $field == 'EmailNotifications' ) || ( $field == 'PhoneNotifications' ) || ( $field == 'SocialNotifications' ) ) {
				$query = $query . $field . " = " . $value . " ";
			}
			else {
				$query = $query . $field . " = '" . ( ( $value == "/" ) ? "" : $value ) . "' ";
			}
				
			$query = $query . "
					  WHERE ( BotId = " . $this->bot_id . " ) AND 
					        ( SocialId = " . $this->id . " )";

			return mysqli_query( $mysqli, $query );
		}
		
		
		public function updateUsers() {
			global $mysqli;
			
			if ( is_null( $this->bot_id ) ) {
				return false;
			}
			
			if ( !$this->registered ) {
				$query = "INSERT INTO Users ( Login, Password, Name, Gender, Email, EmailNotifications, Phone, PhoneNotifications, AppealType, Appeal, Comment, ConsentOnPersonalData, BotId, SocialId, SocialNotifications, RegistrationDate ) 
						  VALUES ( '" . ( empty( $this->username ) ? $this->id : $this->username ) . "', '', '" . $this->full_name . "', " . $this->gender . ", '" . $this->email . "', " . $this->email_notifications . ", '" . $this->phone . "', " . $this->phone_notifications . ", " . $this->appeal_type . ", '" . $this->appeal . "', '" . $this->comment . "', " . $this->consent_on_personal_data . ", " . $this->bot_id . ", " . $this->id . ", " . $this->social_notifications . ", NOW() )";

				return mysqli_query( $mysqli, $query );
			}
			
			$query = "UPDATE Users 
					  SET Login = '" . ( empty( $this->username ) ? $this->id : $this->username ) . "', Name = '" . $this->full_name . "', Gender = " . $this->gender . ", EMail = '" . $this->email . "', EmailNotifications = " . $this->email_notifications . ", Phone = '" . $this->phone . "', PhoneNotifications = " . $this->phone_notifications . ", AppealType = " . $this->appeal_type . ", Appeal = '" . $this->appeal . "', Comment = '" . $this->comment . "', ConsentOnPersonalData = 1, SocialNotifications = " . $this->social_notifications . ", RegistrationDate = NOW() 
					  WHERE ( BotId = " . $this->bot_id . " ) AND 
					        ( SocialId = " . $this->id . " )";

			return mysqli_query( $mysqli, $query );
		}
		
		
		public function getPersonalAccounts() {
			if ( ( is_null( $this->bot_id ) ) || ( is_null( $this->user_id ) ) ) {
				return false;
			}
			
			return commonGetPersonalAccounts( $this->user_id, true );
		}
	}
	

	class telegramBot {
	    private $id                  = null;
	    private $bot_id              = null;
	    private $name                = '';
		private $username			 = '';
		private $apiURL              = 'https://api.telegram.org/bot';
		private $apiToken            = '';
		private $lastUpdateId        = 0;
		private $Webhook             = false;
		public $lastErrorCode        = 0;
		public $lastErrorDescription = '';
	  
	  
		public function __construct( $bot_id = null, $id, $token ) {
			global $mysqli;
			
			$this->bot_id = $bot_id;
			
			if ( is_null( $bot_id ) ) {
				$query = "SELECT Bots.BotId 
						  FROM Bots AS Bots
						  WHERE ( Bots.SocialId = " . $id . " ) AND
						        ( Bots.Token = '" . $token . "' )
						  LIMIT 1";
				
				if ( $result = mysqli_query( $mysqli, $query ) ) { 
					if ( $row = mysqli_fetch_array( $result ) ) { 
						$this->bot_id = $row[ 'BotId' ];
					} 

					$result->close(); 
				} 
			}
			
			if ( !is_null( $this->bot_id ) ) {
				$this->apiToken = $id . ":" . $token;
				$this->getMe();
				
				if ( !is_null( $this->id ) ) {
					$this->getWebhookInfo();
				}
			}
		}
		
		
		public function main( $update ) {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			if ( !is_null( $update ) ) {
				if ( isset( $update->message ) ) {
					if ( $update->message->chat->type == 'private' ) {
						$user = new telegramUser( $this->bot_id, $update->message->from, $update->message->chat->id ); 
						
						if ( !$user->is_bot ) {
							if ( isset( $update->message->reply_to_message ) ) {
								// Регистрация
								if ( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ) == 'фамилия, имя, отчество ("/" - для отказа от ввода):' ) {
									return $this->action( 'registration_fullname_after', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'фамилия, имя, отчество:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_fullname_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'пол:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_gender_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'обращаться как:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_appeal_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ) == 'электронная почта ("/" - для отказа от ввода):' ) {
									return $this->action( 'registration_email_after', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'электронная почта:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_email_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'согласен на получение уведомлений по электронной почте:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_email_notifications_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ) == 'телефон ("/" - для отказа от ввода):' ) {
									return $this->action( 'registration_phone_after', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'телефон:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_phone_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'согласен на получение sms уведомлений:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_phone_notifications_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'согласен на получение telegram-уведомлений:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_social_notifications_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ) == 'дополнительная информация ("/" - для отказа от ввода):' ) {
									return $this->action( 'registration_additionalinfo_after', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'дополнительная информация:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'registration_additionalinfo_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								// Лицевые счета
								else if ( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ) == 'номер лицевого счета ("/" - для отказа от ввода):' ) {
									return $this->action( 'personal_account_add_additionalinfo_after', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'номер лицевого счета:', 0, 'UTF-8' ) !== false ) {
									return $this->action( 'personal_account_add_additionalinfo_edit', $user, trim( $update->message->text ), $update->message->message_id, $update->message->reply_to_message->message_id );
								}
								
								// Приборы учета
								else if ( mb_strpos( mb_strtolower( $update->message->reply_to_message->text, 'UTF-8' ), 'введите текущие показания', 0, 'UTF-8' ) !== false ) {
									$data        = str_replace( 'http://www.data.ru/', '', mb_strtolower( $update->message->reply_to_message->entities[ 0 ]->url, 'UTF-8' ) );
									$indications = trim( $update->message->text );
									$photo       = null;
									
									if ( isset( $update->message->photo ) ) {
										$arguments = array( 'file_id' => $update->message->photo[ count( $update->message->photo ) - 1 ]->file_id );
										$result    = $this->call( 'getFile', $arguments );
										
										if ( !is_null( $result ) ) {
											$photo = str_replace( '/bot', '/file/bot', $this->apiURL ) . $this->apiToken . '/' . $result->result->file_path;
											//$this->sendMessage( $user->id, $photo );											
										}
									}
									
									return $this->action( 'personal_account_device_indications_after', $user, $data . '_' . $indications, $update->message->message_id, $update->message->reply_to_message->message_id );
								}
							}
							
							else if ( isset( $update->message->text ) ) {
								if ( mb_strtolower( $update->message->text, 'UTF-8' ) == 'привет' ) {
									return $this->action( 'greeting', $user, null, $update->message->message_id, null );
								}
								
								else if ( mb_strtolower( $update->message->text, 'UTF-8' ) == 'hello' ) {
									return $this->action( 'greeting', $user, null, $update->message->message_id, null );
								}
								
								else if ( mb_strtolower( $update->message->text, 'UTF-8' ) == '/hi' ) {
									return $this->action( 'greeting', $user, null, $update->message->message_id, null );
								}
								
								else if ( mb_strtolower( $update->message->text, 'UTF-8' ) == '/start' ) {
									return $this->action( 'greeting', $user, null, $update->message->message_id, null );
								}
							}
						}
						
						else {
							// It's bot. Ignore
						}
					}
					
					else {
						// No private. Ignore
					}
				}
				
				else if ( isset( $update->callback_query ) ) {
					if ( isset( $update->callback_query->data ) ) {
						$user = new telegramUser( $this->bot_id, $update->callback_query->from );
						
						if ( !$user->is_bot ) {
							// Регистрация
							if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_yes' ) {
								return $this->action( 'registration_yes_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_no' ) {
								return $this->action( 'registration_no_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_complete' ) {
								return $this->action( 'registration_complete_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_cancel' ) {
								return $this->action( 'registration_cancel_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_gender_male' ) {
								return $this->action( 'registration_gender_after', $user, 1, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_gender_female' ) {
								return $this->action( 'registration_gender_after', $user, 2, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_appeal_1' ) {
								return $this->action( 'registration_appeal_after', $user, 1, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_appeal_2' ) {
								return $this->action( 'registration_appeal_after', $user, 2, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_appeal_3' ) {
								return $this->action( 'registration_appeal_after', $user, 3, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_email_notifications_yes' ) {
								return $this->action( 'registration_email_notifications_after', $user, 1, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_email_notifications_no' ) {
								return $this->action( 'registration_email_notifications_after', $user, 0, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_phone_notifications_yes' ) {
								return $this->action( 'registration_phone_notifications_after', $user, 1, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_phone_notifications_no' ) {
								return $this->action( 'registration_phone_notifications_after', $user, 0, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_social_notifications_yes' ) {
								return $this->action( 'registration_social_notifications_after', $user, 1, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/registration_social_notifications_no' ) {
								return $this->action( 'registration_social_notifications_after', $user, 0, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							// Лицевые счета
							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_choice_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_choice_after', $user, str_replace( '/personal_account_choice_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_refresh_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_refresh_after', $user, str_replace( '/personal_account_refresh_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/personal_account_add' ) {
								return $this->action( 'personal_account_add_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/personal_account_add_company_cancel' ) {
								return $this->action( 'personal_account_add_company_cancel_after', $user, 0, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_add_company_alphabet_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_add_company_alphabet_after', $user, str_replace( '/personal_account_add_company_alphabet_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_add_company_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_add_company_after', $user, str_replace( '/personal_account_add_company_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/personal_account_add_complete' ) {
								return $this->action( 'personal_account_add_complete_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/personal_account_add_cancel' ) {
								return $this->action( 'personal_account_add_cancel_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/personal_account_delete' ) {
								return $this->action( 'personal_account_delete_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_delete_account_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_delete_account_after', $user, str_replace( '/personal_account_delete_account_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_delete_complete_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_delete_complete_after', $user, str_replace( '/personal_account_delete_complete_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/personal_account_delete_cancel' ) {
								return $this->action( 'personal_account_delete_cancel_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
							
							// Отправленные запросы на добавление лицевых счетов
							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/query_accounts' ) {
								return $this->action( 'query_accounts_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/query_accounts_back' ) {
								return $this->action( 'query_accounts_back_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							// Коммунальные услуги
							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_service_choice_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_service_choice_after', $user, str_replace( '/personal_account_service_choice_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_service_refresh_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_service_refresh_after', $user, str_replace( '/personal_account_service_refresh_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/personal_account_service_back' ) {
								return $this->action( 'personal_account_service_back_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							// Приборы учета
							else if ( mb_strpos( mb_strtolower( $update->callback_query->data, 'UTF-8' ), '/personal_account_device_choice_', 0, 'UTF-8' ) !== false ) {
								return $this->action( 'personal_account_device_choice_after', $user, str_replace( '/personal_account_device_choice_', '', mb_strtolower( $update->callback_query->data, 'UTF-8' ) ), $update->callback_query->message->message_id, null, $update->callback_query->id );
							}

							else if ( mb_strtolower( $update->callback_query->data, 'UTF-8' ) == '/personal_account_device_back' ) {
								return $this->action( 'personal_account_device_back_after', $user, null, $update->callback_query->message->message_id, null, $update->callback_query->id );
							}
						}
					}
				}
			}

			return null;
		}
		
		
		private function action( $method, $user, $data = null, $message_id = null, $reply_message_id = null, $callback_query_id = null ) {
			global $mysqli;			
			
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			$user->getUserInfo();
		
			// Регистрация
			if ( $method === 'greeting' ) {
				if ( $user->query ) {
					return $this->action( 'registration_send', $user );
				}
				
				if ( !$user->registered ) {
					return $this->action( 'registration_new_send', $user );
				}
				
				return $this->action( 'menu_send', $user );
			}	
				
			else if ( $method === 'registration_new_send' ) {
				$text     = html_replace_special( 'Здравствуйте, ' . $user->appeal . '!' . chr( 10 ) . 'Для дальнейшей работы требуется регистрация. Продолжить?' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '✅  Да',        'callback_data' => '/registration_yes' ),
																				   array( 'text' => '❌  Не сейчас', 'callback_data' => '/registration_no' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_send' ) {
				$text     = html_replace_special( 'Здравствуйте, ' . $user->appeal . '!' . chr( 10 ) . 'Ваш запрос о регистрации находится в процессе рассмотрения. Повторить?' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '✅  Да',        'callback_data' => '/registration_yes' ),
																				   array( 'text' => '❌  Нет', 'callback_data' => '/registration_no' ),
																				   array( 'text' => 'Аннулировать', 'callback_data' => '/registration_cancel' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_yes_after' ) {
				if ( !is_null( $message_id ) ) {
					$text    = '<b>Регистрация</b>';
					$options = array( 'parse_mode' => 'HTML' );
					
					$this->editMessage( $user->id, $message_id, $text, $options );
				}
				
				return $this->action( 'registration_fullname_send', $user );
			}
			
			else if ( $method === 'registration_no_after' ) {
				if ( !is_null( $message_id ) ) {
					return $this->deleteMessage( $user->id, $message_id );
				}
			}
			
			else if ( $method === 'registration_fullname_send' ) {						
				$text     = html_replace_special( 'Фамилия, имя, отчество ("/" - для отказа от ввода):' );
				$keyboard = json_encode( array( 'force_reply' => true, 
												'selective' => true ), 
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}

				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_fullname_after' ) {
				if ( $user->updateUsersQuery( 'Name', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( !is_null( $reply_message_id ) ) {
					$this->deleteMessage( $user->id, $reply_message_id );
				}
				
				$text    = 'Фамилия, имя, отчество: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );				
				$this->sendMessage( $user->id, $text, $options );

				return $this->action( 'registration_gender_send', $user );
			}
			
			else if ( $method === 'registration_fullname_edit' ) {
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( $user->updateUsersQuery( 'Name', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				$text    = 'Фамилия, имя, отчество: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					
				
				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'registration_gender_send' ) {			
				$text     = html_replace_special( 'Пол:' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '🚹  Мужской', 'callback_data' => '/registration_gender_male' ),
																				   array( 'text' => '🚺  Женский', 'callback_data' => '/registration_gender_female' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_gender_after' ) {
				if ( $user->updateUsersQuery( 'Gender', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$text    = 'Пол: <b>' . html_replace_special( ( ( $data == 1 ) ? 'мужской' : 'женский' ) ) . '</b>';
					$options = array( 'parse_mode' => 'HTML' );					
					$this->editMessage( $user->id, $message_id, $text, $options );
				}
				
				$buttons = array();
				
				if ( !empty( $user->first_name ) && !empty( $user->last_name ) ) {
					$buttons[] = array( array( 'text' => $user->first_name . ' ' . $user->last_name, 'callback_data' => '/registration_appeal_1' ) );
				}

				if ( !empty( $user->first_name ) && !empty( $user->middle_name ) ) {
					$buttons[] = array( array( 'text' => $user->first_name . ' ' . $user->middle_name, 'callback_data' => '/registration_appeal_2' ) );
				}

				if ( !empty( $user->last_name ) ) {
					$buttons[] = array( array( 'text' => ( ( $user->gender == 2 ) ? 'г-жа' : 'г-н' ) . ' ' . $user->last_name, 'callback_data' => '/registration_appeal_3' ) );
				}
				
				if ( count( $buttons ) > 0 ) {
					return $this->action( 'registration_appeal_send', $user, $buttons );
				}
				
				return $this->action( 'registration_email_send', $user );
			}
			
			else if ( $method === 'registration_gender_edit' ) {	
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( ( mb_strtolower( $data, 'UTF-8' ) == 'м' ) ||
					 ( mb_strtolower( $data, 'UTF-8' ) == 'мужской' ) ||
					 ( mb_strtolower( $data, 'UTF-8' ) == 'male' ) ) {
					$data = 1;
				}
				else {
					$data = 2;
				}
					
				if ( $user->updateUsersQuery( 'Gender', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				$text    = 'Пол: <b>' . html_replace_special( ( ( $data == 1 ) ? 'мужской' : 'женский' ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					

				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'registration_appeal_send' ) {
				$text     = html_replace_special( 'Как я могу к Вам обращаться?' );
				$keyboard = json_encode( array( 'inline_keyboard' => $data ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_appeal_after' ) {
				if ( $user->updateUsersQuery( 'AppealType', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$text    = 'Обращаться как: <b>' . html_replace_special( ( ( $data == 1 ) ? $user->first_name . ' ' . $user->last_name : ( ( $data == 2 ) ? $user->first_name . ' ' . $user->middle_name : ( ( $user->gender == 2 ) ? 'г-жа' : 'г-н' ) . ' ' . $user->last_name ) ) ) . '</b>';
					$options = array( 'parse_mode' => 'HTML' );					
					$this->editMessage( $user->id, $message_id, $text, $options );
				}
				
				return $this->action( 'registration_email_send', $user );
			}
			
			else if ( $method === 'registration_appeal_edit' ) {	
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( mb_strtolower( $data, 'UTF-8' ) == ( $user->first_name . ' ' . $user->last_name ) ) {
					$data = 1;
				}
				else if ( mb_strtolower( $data, 'UTF-8' ) == ( $user->first_name . ' ' . $user->middle_name ) ) {
					$data = 2;
				}
				else if ( mb_strtolower( $data, 'UTF-8' ) == ( ( ( $user->gender == 2 ) ? 'г-жа' : 'г-н' ) . ' ' . $user->last_name ) ) {
					$data = 3;
				}
				else {
					$data = 1;
				}
					
				if ( $user->updateUsersQuery( 'AppealType', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				$text    = 'Обращаться как: <b>' . html_replace_special( ( ( $data == 1 ) ? $user->name : ( ( $data == 2 ) ? $user->first_name . ' ' . $user->middle_name : ( ( $user->gender == 2 ) ? 'г-жа' : 'г-н' ) . ' ' . $user->last_name ) ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					

				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'registration_email_send' ) {
				$text     = html_replace_special( 'Электронная почта ("/" - для отказа от ввода):' );
				$keyboard = json_encode( array( 'force_reply' => true, 
												'selective' => true ), 
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_email_after' ) {
				if ( $user->updateUsersQuery( 'Email', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( !is_null( $reply_message_id ) ) {
					$this->deleteMessage( $user->id, $reply_message_id );
				}
				
				$text    = 'Электронная почта: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					
				$this->sendMessage( $user->id, $text, $options );

				if ( $data == "/" ) {
					return $this->action( 'registration_phone_send', $user );
				}
				
				return $this->action( 'registration_email_notifications_send', $user );
			}
			
			else if ( $method === 'registration_email_edit' ) {
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( $user->updateUsersQuery( 'Email', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				$text    = 'Электронная почта: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					
				
				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'registration_email_notifications_send' ) {			
				$text     = html_replace_special( 'Согласен на получение уведомлений по электронной почте:' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '✅  Да',  'callback_data' => '/registration_email_notifications_yes' ),
																				   array( 'text' => '❌  Нет', 'callback_data' => '/registration_email_notifications_no' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_email_notifications_after' ) {
				if ( $user->updateUsersQuery( 'EmailNotifications', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$text    = 'Согласен на получение уведомлений по электронной почте: <b>' . html_replace_special( ( ( $data == 1 ) ? 'да' : 'нет' ) ) . '</b>';
					$options = array( 'parse_mode' => 'HTML' );					
					$this->editMessage( $user->id, $message_id, $text, $options );
				}
				
				return $this->action( 'registration_phone_send', $user );
			}
			
			else if ( $method === 'registration_email_notifications_edit' ) {	
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( ( mb_strtolower( $data, 'UTF-8' ) == 'да' ) ||
					 ( mb_strtolower( $data, 'UTF-8' ) == 'yes' ) ) {
					$data = 1;
				}
				else {
					$data = 0;
				}
					
				if ( $user->updateUsersQuery( 'EmailNotifications', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				$text    = 'Согласен на получение уведомлений по электронной почте: <b>' . html_replace_special( ( ( $data == 1 ) ? 'да' : 'нет' ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					

				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'registration_phone_send' ) {
				$text     = html_replace_special( 'Телефон ("/" - для отказа от ввода):' );
				$keyboard = json_encode( array( 'force_reply' => true, 
												'selective' => true ), 
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}

				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
						
			else if ( $method === 'registration_phone_after' ) {		
				if ( $user->updateUsersQuery( 'Phone', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}

				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( !is_null( $reply_message_id ) ) {
					$this->deleteMessage( $user->id, $reply_message_id );
				}
				
				$text    = 'Телефон: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					
				$this->sendMessage( $user->id, $text, $options );

				if ( $data == "/" ) {
					return $this->action( 'registration_social_notifications_send', $user );
				}
				
				return $this->action( 'registration_phone_notifications_send', $user );
			}
					
			else if ( $method === 'registration_phone_edit' ) {		
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( $user->updateUsersQuery( 'Phone', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}

				$text    = 'Телефон: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					
				
				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
					
			else if ( $method === 'registration_phone_notifications_send' ) {			
				$text     = html_replace_special( 'Согласен на получение SMS уведомлений:' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '✅  Да',  'callback_data' => '/registration_phone_notifications_yes' ),
																				   array( 'text' => '❌  Нет', 'callback_data' => '/registration_phone_notifications_no' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_phone_notifications_after' ) {
				if ( $user->updateUsersQuery( 'PhoneNotifications', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$text    = 'Согласен на получение SMS уведомлений: <b>' . html_replace_special( ( ( $data == 1 ) ? 'да' : 'нет' ) ) . '</b>';
					$options = array( 'parse_mode' => 'HTML' );					
					$this->editMessage( $user->id, $message_id, $text, $options );
				}
				
				return $this->action( 'registration_social_notifications_send', $user );
			}
			
			else if ( $method === 'registration_phone_notifications_edit' ) {	
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( ( mb_strtolower( $data, 'UTF-8' ) == 'да' ) ||
					 ( mb_strtolower( $data, 'UTF-8' ) == 'yes' ) ) {
					$data = 1;
				}
				else {
					$data = 0;
				}
					
				if ( $user->updateUsersQuery( 'PhoneNotifications', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				$text    = 'Согласен на получение SMS уведомлений: <b>' . html_replace_special( ( ( $data == 1 ) ? 'да' : 'нет' ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					

				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'registration_social_notifications_send' ) {			
				$text     = html_replace_special( 'Согласен на получение telegram-уведомлений:' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '✅  Да',  'callback_data' => '/registration_social_notifications_yes' ),
																				   array( 'text' => '❌  Нет', 'callback_data' => '/registration_social_notifications_no' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
			
			else if ( $method === 'registration_social_notifications_after' ) {
				if ( $user->updateUsersQuery( 'SocialNotifications', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$text    = 'Согласен на получение telegram-уведомлений: <b>' . html_replace_special( ( ( $data == 1 ) ? 'да' : 'нет' ) ) . '</b>';
					$options = array( 'parse_mode' => 'HTML' );					
					$this->editMessage( $user->id, $message_id, $text, $options );
				}
				
				return $this->action( 'registration_additionalinfo_send', $user );
			}
			
			else if ( $method === 'registration_social_notifications_edit' ) {	
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( ( mb_strtolower( $data, 'UTF-8' ) == 'да' ) ||
					 ( mb_strtolower( $data, 'UTF-8' ) == 'yes' ) ) {
					$data = 1;
				}
				else {
					$data = 0;
				}
					
				if ( $user->updateUsersQuery( 'SocialNotifications', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				$text    = 'Согласен на получение telegram-уведомлений: <b>' . html_replace_special( ( ( $data == 1 ) ? 'да' : 'нет' ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					

				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'registration_additionalinfo_send' ) {
				$text     = html_replace_special( 'Дополнительная информация ("/" - для отказа от ввода):' );
				$keyboard = json_encode( array( 'force_reply' => true, 
												'selective' => true ), 
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}

				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'registration_additionalinfo_after' ) {	
				if ( $user->updateUsersQuery( 'Comment', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( !is_null( $reply_message_id ) ) {
					$this->deleteMessage( $user->id, $reply_message_id );
				}
				
				$text    = 'Дополнительная информация: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					
				$this->sendMessage( $user->id, $text, $options );

				return $this->action( 'registration_complete_send', $user );
			}
			
			else if ( $method === 'registration_additionalinfo_edit' ) {	
				if ( !$user->query ) {
					return null;
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( $user->updateUsersQuery( 'Comment', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				$text    = 'Дополнительная информация: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					

				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'registration_complete_send' ) {	
				$text     = html_replace_special( 'Проверьте указанную выше информацию. Подтвердить запрос о регистрации (положительный ответ означает Ваше согласие на обработку персональных данных)?' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '✅  Да',  'callback_data' => '/registration_complete' ),
																				   array( 'text' => '❌  Нет', 'callback_data' => '/registration_cancel' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'registration_complete_after' ) {
				if ( $user->deleteUsersQuery() === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( $user->updateUsers() === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры регистрации (' . $this->lastErrorCode . '). Обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				$text = html_replace_special( $user->appeal . '! Поздравляем с успешным завершением процедуры регистрации.' . chr( 10 ) . 'Чтобы начать работу наберите "/start" или "Привет".' );
				return $this->sendMessage( $user->id, $text );
			}
			
			else if ( $method === 'registration_cancel_after' ) {
				if ( $user->deleteUsersQuery() === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, 'Ошибка выполнения процедуры "Отказ от регистрации" (' . $this->lastErrorCode . '). Обратитесь к администратору.' );
				}
				
				if ( !is_null( $message_id ) ) {
					return $this->deleteMessage( $user->id, $message_id );
				}
			}
			
			else if ( $method === 'menu_send' ) {
				$text = html_replace_special( 'Здравствуйте, ' . $user->appeal . '!' );
				$this->sendMessage( $user->id, $text );
				
				return $this->action( 'personal_account_send', $user );
			}
			
			// Лицевые счета
			else if ( $method === 'personal_account_send' ) {	
				$data = $user->getPersonalAccounts();
			
				if ( $data === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры "Получить список лицевых счетов" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' ) );
				}
				
				if ( is_null( $data ) ) {
					$text = html_replace_special( '⚠️  Данных о Ваших лицевых счетах не найдено.' );
				}
				else {
					$text = html_replace_special( 'Ваши лицевые счета:' );
				
					foreach ( $data as $value ) {
						$keyboard[] = array( array( 'text' => $value[ 'account_name' ] . ' (' . $value[ 'object_address' ] . ')', 'callback_data' => '/personal_account_choice_' . $value[ 'account_id' ] ) );
					}
				}
			
				if ( is_null( $data ) ) {
					$keyboard[] = array( array( 'text' => '➕  Добавить ...', 'callback_data' => '/personal_account_add' ) );
				}
				else {
					$keyboard[] = array( array( 'text' => '➕  Добавить ...', 'callback_data' => '/personal_account_add' ),
										 array( 'text' => '➖  Удалить ...', 'callback_data' => '/personal_account_delete' ) );
				}
				
				$keyboard[] = array( array( 'text' => '❓  Необработанные запросы', 'callback_data' => '/query_accounts' ) );
    
				$keyboard = json_encode( array( 'inline_keyboard' => $keyboard ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
					
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}

			else if ( $method === 'personal_account_add_after' ) {
				$company_list = commonGetCompanyList();
				
				if ( $company_list === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					$text = html_replace_special( 'Ошибка выполнения процедуры "Получить список управляющих компаний" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
				
				if ( count( $company_list ) < 10 ) {
					$text = html_replace_special( 'Выберите управляющую компанию:' );
					
					foreach ( $company_list as $value ) {
						$keyboard[] = array( array( 'text' => $value[ 'name' ], 'callback_data' => '/personal_account_add_company_' . $value[ 'id' ] ) );
					}
				}
				else {
					$company_list = commonGetCompanyAlphabet();
					
					if ( $company_list === false ) {
						$this->lastErrorCode        = mysqli_errno( $mysqli );
						$this->lastErrorDescription = mysqli_error( $mysqli );
						
						$text = html_replace_special( 'Ошибка выполнения процедуры "Получить список управляющих компаний" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

						if ( !is_null( $callback_query_id ) ) {
							$arguments = array( 'callback_query_id' => $callback_query_id,
												'text'              => $text, 
												'show_alert'        => true,
												'cache_time'        => 3 );
							return $this->call( 'answerCallbackQuery', $arguments );
						}

						return $this->sendMessage( $user->id, $text );
					}
					
					$text = html_replace_special( 'Выберите управляющую компанию (алфавитный список):' );
					
					foreach ( $company_list as $value ) {
						$keyboard[] = array( 'text' => $value, 'callback_data' => '/personal_account_add_company_alphabet_' . $value );
					}

					$keyboard = array( $keyboard );
				}
				
				$keyboard[] = array( array( 'text' => '🚫  Отмена', 'callback_data' => '/personal_account_add_company_cancel' ) );
	
				$keyboard = json_encode( array( 'inline_keyboard' => $keyboard ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'personal_account_add_company_alphabet_after' ) {
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				$company_list = commonGetCompanyList( $data );
				
				if ( $company_list === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					$text = html_replace_special( 'Ошибка выполнения процедуры "Получить список управляющих компаний" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
				
				foreach ( $company_list as $value ) {
					$keyboard[] = array( array( 'text' => $value[ 'name' ], 'callback_data' => '/personal_account_add_company_' . $value[ 'id' ] ) );
				}
				
				$keyboard[] = array( array( 'text' => '🚫  Отмена', 'callback_data' => '/personal_account_add_company_cancel' ) );
	
				$keyboard = json_encode( array( 'inline_keyboard' => $keyboard ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$text    = html_replace_special( 'Выберите управляющую компанию:' );
				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'personal_account_add_company_after' ) {
				if ( commonUpdateAccountQuery( $user->user_id, 'CompanyId', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					$text = html_replace_special( 'Ошибка выполнения процедуры "Добавить запрос о новом лицевом счете" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
	
				if ( !is_null( $message_id ) ) {
					$text    = 'Управляющая компания: <b>' . html_replace_special( commonGetCompanyName( $data ) ) . '</b>';
					$options = array( 'parse_mode' => 'HTML' );					
					$this->editMessage( $user->id, $message_id, $text, $options );
				}
				
				return $this->action( 'personal_account_add_additionalinfo_send', $user );
			}
			
			else if ( $method === 'personal_account_add_company_cancel_after' ) {
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
			}

			else if ( $method === 'personal_account_add_additionalinfo_send' ) {
				$text     = html_replace_special( 'Номер лицевого счета ("/" - для отказа от ввода):' );
				$keyboard = json_encode( array( 'force_reply' => true, 
												'selective'   => true ), 
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}

				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'personal_account_add_additionalinfo_after' ) {	
				if ( commonUpdateAccountQuery( $user->user_id, 'AccountName', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры "Добавить запрос о новом лицевом счете" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' ) );
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( !is_null( $reply_message_id ) ) {
					$this->deleteMessage( $user->id, $reply_message_id );
				}

				$text    = 'Номер лицевого счета: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );				
				$this->sendMessage( $user->id, $text, $options );

				return $this->action( 'personal_account_add_complete_send', $user );
			}
			
			else if ( $method === 'personal_account_add_additionalinfo_edit' ) {
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( commonUpdateAccountQuery( $user->user_id, 'AccountName', $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры "Добавить запрос о новом лицевом счете" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' ) );
				}
				
				$text    = 'Номер лицевого счета: <b>' . html_replace_special( ( ( $data == "/" ) ? "" : $data ) ) . '</b>';
				$options = array( 'parse_mode' => 'HTML' );					
				
				return $this->editMessage( $user->id, $reply_message_id, $text, $options );
			}
			
			else if ( $method === 'personal_account_add_complete_send' ) {	
				$text     = html_replace_special( 'Подтвердить запрос о добавлении нового лицевого счета?' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '✅  Да',  'callback_data' => '/personal_account_add_complete' ),
																				   array( 'text' => '❌  Нет', 'callback_data' => '/personal_account_add_cancel' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'personal_account_add_complete_after' ) {
				if ( commonUpdateAccountQuery( $user->user_id, 'QueryStatus', 0 ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					$text = html_replace_special( 'Ошибка выполнения процедуры "Добавить запрос о новом лицевом счете" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
				
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				$text = html_replace_special( 'Ваш запрос о добавлении лицевого счета будет рассмотрен в ближайшее время.' . chr( 10 ) . 'Для продолжения работы наберите "/start".' );
				return $this->sendMessage( $user->id, $text );
			}
			
			else if ( $method === 'personal_account_add_cancel_after' ) {
				if ( commonUpdateAccountQuery( $user->user_id, 'QueryStatus', -1 ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					$text = html_replace_special( 'Ошибка выполнения процедуры "Отменить запрос о новом лицевом счете" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
				
				if ( !is_null( $message_id ) ) {
					return $this->deleteMessage( $user->id, $message_id );
				}
			}

			else if ( $method === 'personal_account_delete_after' ) {
				$data = $user->getPersonalAccounts();
			
				if ( $data === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					$text = html_replace_special( 'Ошибка выполнения процедуры "Получить список лицевых счетов" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
				
				$text = html_replace_special( 'Выберите лицевой счет для удаления:' );
				
				foreach ( $data as $value ) {
					$keyboard[] = array( array( 'text' => $value[ 'account_name' ] . ' (' . $value[ 'object_address' ] . ')', 'callback_data' => '/personal_account_delete_account_' . $value[ 'account_id' ] ) );
				}
				
				$keyboard[] = array( array( 'text' => '🚫  Отмена', 'callback_data' => '/personal_account_delete_cancel' ) );
	
				$keyboard = json_encode( array( 'inline_keyboard' => $keyboard ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'personal_account_delete_account_after' ) {
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				$text     = html_replace_special( 'Удалить выбранный лицевой счет?' );
				$keyboard = json_encode( array( 'inline_keyboard' => array( array( array( 'text' => '✅  Да',  'callback_data' => '/personal_account_delete_complete_' . $data ),
																				   array( 'text' => '❌  Нет', 'callback_data' => '/personal_account_delete_cancel' ) ) ) ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode' => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'personal_account_delete_complete_after' ) {
				if ( commonDeleteAccountUser( $user->user_id, $data ) === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					$text = html_replace_special( 'Ошибка выполнения процедуры "Удалить лицевой счет" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}

				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				return $this->action( 'personal_account_send', $user );
			}
			
			else if ( $method === 'personal_account_delete_cancel_after' ) {
				if ( !is_null( $message_id ) ) {
					return $this->deleteMessage( $user->id, $message_id );
				}
			}

			else if ( ( $method === 'personal_account_choice_after' ) || ( $method === 'personal_account_refresh_after' ) ) {
				$account_info = commonGetAccountInfo( $user->user_id, $data );
			
				if ( $account_info === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );

					$text = html_replace_special( 'Ошибка выполнения процедуры "Детализация лицевого счета" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
				
				if ( is_null( $account_info ) ) {
					$text = html_replace_special( '⚠️  Детализация лицевого счета недоступна (лицевой счет не используется или удален).' );
					
				}
				else {
					$text  = '📘  Лицевой счет: <b>' . html_replace_special( $account_info[ 'account' ][ 'name' ] ) . '</b>';
					$text  = $text . chr( 10 ) . 'Абонент: <b>' . html_replace_special( $account_info[ 'abonent' ][ 'name' ] ) . '</b>';
					$text  = $text . chr( 10 ) . 'Объект недвижимости: <b>' . html_replace_special( $account_info[ 'object' ][ 'name' ] ) . '</b>';
					$text  = $text . chr( 10 ) . 'Текущая задолженность: <b>' . ( ( $account_info[ 'debt' ] <= 0 ) ? 'отсутствует' : number_format( $account_info[ 'debt' ], 2, ',', '' ) . ' ₽' ) . '</b>';
					$text  = $text . chr( 10 ) . 'К оплате в след. периоде: <b>' . number_format( $account_info[ 'summa' ], 2, ',', '' ) . ' ₽</b>, в том числе:' . chr( 10 );
					$index = 1;

					foreach ( $account_info[ 'services' ] as $value ) {
						if ( $value[ 'calculation' ][ 'type' ] == 1 ) {
							$service = $index . ') ' . $value[ 'service' ][ 'name' ] . ' (объем: ' . number_format( $value[ 'normative' ], 3, ',', '' ) . '; сумма: ' . number_format( $value[ 'summa' ], 2, ',', '' ) . ' ₽)';
						}
						else if ( $value[ 'calculation' ][ 'type' ] == 2 ) {
							$service = $index . ') ' . $value[ 'service' ][ 'name' ] . ' (объем: ' . number_format( $value[ 'indications' ], 3, ',', '' ) . '; сумма: ' . number_format( $value[ 'summa' ], 2, ',', '' ) . ' ₽)';
						}
						else if ( $value[ 'calculation' ][ 'type' ] == 3 ) {
							$service = $index . ') ' . $value[ 'service' ][ 'name' ] . ' (сумма: ' . number_format( $value[ 'summa' ], 2, ',', '' ) . ' ₽)';
						}
						else if ( $value[ 'calculation' ][ 'type' ] == 4 ) {
							$service = $index . ') ' . $value[ 'service' ][ 'name' ] . ', ОДН (объем: ' . number_format( $value[ 'shared_indications' ], 3, ',', '' ) . '; сумма: ' . number_format( $value[ 'summa' ], 2, ',', '' ) . ' ₽)';
						}
						else if ( $value[ 'calculation' ][ 'type' ] == 5 ) {
							$service = $index . ') ' . $value[ 'service' ][ 'name' ] . ', ОДН (объем: ' . number_format( $value[ 'shared_indications' ], 3, ',', '' ) . '; сумма: ' . number_format( $value[ 'summa' ], 2, ',', '' ) . ' ₽)';
						}
						
						if ( $account_info[ 'access' ] ) {
							$keyboard[] = array( array( 'text' => ( ( $value[ 'calculation' ][ 'type' ] == 2 ) ? '✏️  ' : '📌  ' ) . $service . str_repeat( ' ', 150 ) . '_', 'callback_data' => '/personal_account_service_choice_' . $account_info[ 'account' ][ 'id' ] . '_' . $value[ 'service' ][ 'id' ] . '_' . $value[ 'tariff' ][ 'id' ] . '_' . $value[ 'calculation' ][ 'type' ] ) );
						}
						else {
							$text = $text . chr( 10 ) . ' <code>' . $service . '</code>';
						}
						
						$index = $index + 1;
					}
				}
			
				if ( $account_info[ 'access' ] ) {
					$keyboard[] = array( array( 'text' => '🔄  Обновить', 'callback_data' => '/personal_account_refresh_' . $data ),
										 array( 'text' => '🔙  Назад', 'callback_data' => '/personal_account_service_back' ) );    
				}
				else {
					$keyboard[] = array( array( 'text' => '🔙  Назад', 'callback_data' => '/personal_account_service_back' ) );    
				}
				
				$keyboard   = json_encode( array( 'inline_keyboard' => $keyboard ),
										   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
					
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
								  
				if ( $method === 'personal_account_refresh_after' ) {
					return $this->editMessage( $user->id, $message_id, $text, $options );
				}
								  
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( ( $method === 'personal_account_service_choice_after' ) || ( $method === 'personal_account_service_refresh_after' ) ) {
				$data         = explode( '_', $data, 4 );
				$account_info = commonGetAccountInfo( $user->user_id, $data[ 0 ], $data[ 1 ], $data[ 2 ], $data[ 3 ] );
			
				if ( $account_info === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );
					
					$text = html_replace_special( 'Ошибка выполнения процедуры "Детализация лицевого счета" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
				
				if ( is_null( $account_info ) ) {
					$text = html_replace_special( '⚠️  Детализация коммунальной услуги недоступна.' );
					
				}
				else {
					$text = '<b>' . html_replace_special( $account_info[ 'services' ][ 0 ][ 'service' ][ 'name' ] );
					if ( ( $account_info[ 'services' ][ 0 ][ 'calculation' ][ 'type' ] == 4 ) || ( $account_info[ 'services' ][ 0 ][ 'calculation' ][ 'type' ] == 5 ) ) {
						$text  = $text . html_replace_special( ', ОДН' );
					}					
					$text = $text . '</b>';
					$text = $text . chr( 10 ) . ' <code>Вид расчета: <i>' . html_replace_special( $account_info[ 'services' ][ 0 ][ 'calculation' ][ 'name' ] ) . '</i></code>';
					if ( $account_info[ 'services' ][ 0 ][ 'calculation' ][ 'type' ] != 3 ) {
						$text = $text . chr( 10 ) . ' <code>Объем:       <i>' . number_format( $account_info[ 'services' ][ 0 ][ 'volume' ], 3, ',', '' ) . ' ' . $account_info[ 'services' ][ 0 ][ 'unit' ][ 'name' ] . '</i></code>';
					}
					$text = $text . chr( 10 ) . ' <code>Коэффициент: <i>' . number_format( $account_info[ 'services' ][ 0 ][ 'calculation' ][ 'coefficient' ], 3, ',', '' ) . '</i></code>';
					$text = $text . chr( 10 ) . ' <code>Тариф:       <i>' . number_format( $account_info[ 'services' ][ 0 ][ 'tariff' ][ 'price' ], 2, ',', '' ) . ' ₽</i></code>';
					$text = $text . chr( 10 ) . ' <code>Сумма:       <i>' . number_format( $account_info[ 'services' ][ 0 ][ 'summa' ], 2, ',', '' ) . ' ₽</i></code>';
					
					if ( count( $account_info[ 'services' ][ 0 ][ 'devices' ] ) > 0 ) {
						$text = $text . chr( 10 ) . chr( 10 ) . html_replace_special( 'Приборы учета:' );
						
						foreach ( $account_info[ 'services' ][ 0 ][ 'devices' ] as $value ) {
							$keyboard[] = array( array( 'text' => $value[ 'name' ] . ' (текущие: ' . number_format( $value[ 'current_indications' ], 3, ',', '' ) . '; предыдущие: ' . number_format( $value[ 'previous_indications' ], 3, ',', '' ) . ')' . str_repeat( ' ', 150 ) . '_', 'callback_data' => '/personal_account_device_choice_' . $data[ 0 ] . '_' . $data[ 1 ] . '_' . $data[ 2 ] . '_' . $data[ 3 ] . '_' . $value[ 'id' ] . '_' . $message_id ) );
						}
					}
				}
			
				if ( $account_info[ 'services' ][ 0 ][ 'calculation' ][ 'type' ] == 2 ) {
					$keyboard[] = array( array( 'text' => '🔄  Обновить', 'callback_data' => '/personal_account_service_refresh_' . $data[ 0 ] . '_' . $data[ 1 ] . '_' . $data[ 2 ] . '_' . $data[ 3 ] ),
										 array( 'text' => '🔙  Назад', 'callback_data' => '/personal_account_device_back' ) );
				}
				else {
					$keyboard[] = array( array( 'text' => '🔙  Назад', 'callback_data' => '/personal_account_device_back' ) );
				}
				
				$keyboard = json_encode( array( 'inline_keyboard' => $keyboard ),
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
					
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
								  
				if ( $method === 'personal_account_service_refresh_after' ) {
					return $this->editMessage( $user->id, $message_id, $text, $options );
				}
								  
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'personal_account_service_back_after' ) {
				if ( !is_null( $message_id ) ) {
					return $this->deleteMessage( $user->id, $message_id );
				}
			}
			
			else if ( $method === 'personal_account_device_choice_after' ) {
				$text     = html_replace_special( 'Введите текущие показания ("/" - для отказа от ввода): ' ) . '<a href="http://www.data.ru/' . $data . '_' . $message_id . '">_</a>';				
				$keyboard = json_encode( array( 'force_reply' => true, 
												'selective'   => true ), 
										 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}

				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'personal_account_device_back_after' ) {
				if ( !is_null( $message_id ) ) {
					return $this->deleteMessage( $user->id, $message_id );
				}
			}

			else if ( $method === 'personal_account_device_indications_after' ) {
				if ( !is_null( $message_id ) ) {
					$this->deleteMessage( $user->id, $message_id );
				}
				
				if ( !is_null( $reply_message_id ) ) {
					$this->deleteMessage( $user->id, $reply_message_id );
				}
				
				$data = explode( '_', $data, 8 );
								
				if ( $data[ 7 ] == "/" ) {
					$data[ 7 ] = "";
				}
				
				if ( !empty( $data[ 7 ] ) ) {
					if ( commonUpdateDeviceIndications( $data[ 4 ], $data[ 7 ] ) === false ) {
						$this->lastErrorCode        = mysqli_errno( $mysqli );
						$this->lastErrorDescription = mysqli_error( $mysqli );
						
						return $this->sendMessage( $user->id, html_replace_special( 'Ошибка выполнения процедуры "Добавить показания прибора учета" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' ) );
					}
					
//					if ( !is_null( $data[ 5 ] ) ) {
//						$this->action( 'personal_account_refresh_after', $user, $data[ 0 ], $data[ 5 ], null, null );
//					}

//					if ( !is_null( $data[ 6 ] ) ) {
//						return $this->action( 'personal_account_service_refresh_after', $user, $data[ 0 ] . '_' . $data[ 1 ] . '_' . $data[ 2 ] . '_' . $data[ 3 ], $data[ 6 ], null, null );
//					}
				}
			}
			
			else if ( $method === 'query_accounts_after' ) {
				$query_accounts = commonGetQueryAccounts( $user->user_id, false );
			
				if ( $query_accounts === false ) {
					$this->lastErrorCode        = mysqli_errno( $mysqli );
					$this->lastErrorDescription = mysqli_error( $mysqli );

					$text = html_replace_special( 'Ошибка выполнения процедуры "Получить запросы пользователей" (' . $this->lastErrorCode . '). Попробуйте еще раз или обратитесь к администратору.' );

					if ( !is_null( $callback_query_id ) ) {
						$arguments = array( 'callback_query_id' => $callback_query_id,
											'text'              => $text, 
											'show_alert'        => true,
											'cache_time'        => 3 );
						return $this->call( 'answerCallbackQuery', $arguments );
					}

					return $this->sendMessage( $user->id, $text );
				}
				
				if ( is_null( $query_accounts ) ) {
					$text = html_replace_special( '⚠️  Необработанных запросов пользователя не найдено.' );
					
				}
				else {
					$text  = '<b>Необработанные запросы</b>:' . chr( 10 );
					$index = 1;

					foreach ( $query_accounts as $value ) {
						$text  = $text . chr( 10 ) . ' <code>' . $index . ') Дата запроса: <i>' . date_format( date_create( $value[ 'query' ][ 'date' ] ), 'd.m.Y' ) . '</i></code>';
						$text  = $text . chr( 10 ) . ' <code>' . str_repeat( ' ', strlen( $index ) ) . '  Управляющая компания: <i>' . $value[ 'company' ][ 'name' ] . '</i></code>';
						$text  = $text . chr( 10 ) . ' <code>' . str_repeat( ' ', strlen( $index ) ) . '  Номер лицевого счета: <i>' . $value[ 'query' ][ 'text' ] . '</i></code>';
						$text  = $text . chr( 10 ) . ' <code>' . str_repeat( ' ', strlen( $index ) ) . '  Статус:               <i>' . $value[ 'query' ][ 'status_name' ] . '</i></code>';
						if ( $value[ 'query' ][ 'status' ] == 2 ) {
							$text  = $text . chr( 10 ) . ' <code>' . str_repeat( ' ', strlen( $index ) ) . '  Причины отказа:       <i>' . $value[ 'query' ][ 'answer' ] . '</i></code>';
						}
						$index = $index + 1;
					}
				}
			
				$keyboard[] = array( array( 'text' => '🔙  Назад', 'callback_data' => '/query_accounts_back' ) );    
				$keyboard   = json_encode( array( 'inline_keyboard' => $keyboard ),
										   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
					
				if ( $keyboard === false ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					
					return null;
				}
				
				$options = array( 'parse_mode'   => 'HTML',
								  'reply_markup' => $keyboard );
								  
				return $this->sendMessage( $user->id, $text, $options );
			}
				
			else if ( $method === 'query_accounts_back_after' ) {
				if ( !is_null( $message_id ) ) {
					return $this->deleteMessage( $user->id, $message_id );
				}
			}
			
			return null;
		}

		
		private function call( $method, array $arguments = null ) { 
			$cURL = curl_init();
			
			curl_setopt( $cURL, CURLOPT_URL, $this->apiURL . $this->apiToken . '/' . $method );
			curl_setopt( $cURL, CURLOPT_HEADER, false );
			curl_setopt( $cURL, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $cURL, CURLOPT_POST, null );
			curl_setopt( $cURL, CURLOPT_POSTFIELDS, null );
			curl_setopt( $cURL, CURLOPT_SSL_VERIFYPEER, 0 );
			curl_setopt( $cURL, CURLOPT_CONNECTTIMEOUT, 30 );
			
			// curl_setopt( $cURL, CURLOPT_PROXY, '156.235.194.213' );
			// curl_setopt( $cURL, CURLOPT_PROXYPORT, '8080' );
			// curl_setopt( $cURL, CURLOPT_PROXYTYPE, CURLPROXY_HTTP );
			
			// curl_setopt( $cURL, CURLOPT_PROXY, 'tgsock.tk' );
			// curl_setopt( $cURL, CURLOPT_PROXYPORT, '1080' );
			// curl_setopt( $cURL, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5 );
			// curl_setopt( $cURL, CURLOPT_PROXYUSERPWD, 'tglive:tglive1' );
			
			if ( !is_null( $arguments ) ) {
				curl_setopt( $cURL, CURLOPT_POST, true );
				curl_setopt( $cURL, CURLOPT_POSTFIELDS, $arguments );
			}
						
			$result = curl_exec( $cURL );
			
			if ( !$result ) 
			{
				$this->lastErrorCode        = curl_errno( $cURL );
				$this->lastErrorDescription = curl_error( $cURL );
				$result                     = null;
			} 
			else {
				$result = json_decode( $result, false );
				
				if ( ( $result === false ) || is_null( $result ) ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					$result                     = null;
				}				
				else if ( !$result->ok ) {
					$this->lastErrorCode        = $result->error_code;
					$this->lastErrorDescription = $result->description;
					$result                     = null;
				}
			}

			curl_close( $cURL );			
			
			return $result;			
		}	
	
	
		// https://api.telegram.org/bot847811843:AAFFoUt8fKVdxPxAktSGwds_JRZnEfFIRxc/getMe
		// {"ok":true,"result":{"id":847811843,"is_bot":true,"first_name":"PublicService26","username":"PublicService26Bot","can_join_groups":false,"can_read_all_group_messages":false,"supports_inline_queries":false}}
		// {"ok":false,"error_code":401,"description":"Unauthorized"}
		// {"ok":false,"error_code":404,"description":"Not Found"}
		public function getMe() {
			$result = $this->call( 'getMe' );

			if ( !is_null( $result ) ) {
				if ( $result->result->is_bot ) {
					$this->id       = $result->result->id;
					$this->name     = $result->result->first_name;
					$this->username = $result->result->username;
				}
			}
			
			return $result;
		}  
		  
	
		// https://api.telegram.org/bot847811843:AAFFoUt8fKVdxPxAktSGwds_JRZnEfFIRxc/getUpdates?offset=0&limit=10&timeout=0
		// {"ok":true,"result":[{"update_id":397918188, "message":{"message_id":829,"from":{"id":363578712,"is_bot":false,"first_name":"\u0410\u043b\u0435\u043a\u0441\u0435\u0439", ...
		// {"ok":false,"error_code":409,"description":"Conflict: can't use getUpdates method while webhook is active; use deleteWebhook to delete the webhook first"}
		public function getUpdates( $offset = 0, $limit = 100, $timeout = 0 ) {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			if ( $this->Webhook ) {
				$this->lastErrorCode        = 409;
				$this->lastErrorDescription = 'This method will not work if an outgoing webhook is set up';

				return null;
			}
			
			$arguments = array( 'offset' => $offset, 'limit' => $limit, 'timeout' => $timeout );
			$result    = $this->call( 'getUpdates', $arguments );
			
			if ( is_null( $result ) ) {
				return null;
			}
			
			return $result->result;
		}
		  
	
		// Получить последнюю запись из списка входящих сообщений
		public function getLastUpdate( $timeout = 0 ) {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			if ( $this->Webhook ) {
				$input  = file_get_contents( 'php://input' );
				$result = json_decode( $input, false );
				
				if ( ( $result === false ) || is_null( $result ) ) {
					$this->lastErrorCode        = json_last_error();
					$this->lastErrorDescription = json_last_error_msg();
					$result                     = null;
				}				

				return $result;
			}

			$result = $this->getUpdates( $this->lastUpdateId + 1, 100, $timeout );

			if ( is_null( $result ) ) {
				return null;
			}

			$this->lastUpdateId = $result[ count( $result ) - 1 ]->update_id;
			
			return $result[ count( $result ) - 1 ];
		}


		// https://api.telegram.org/bot847811843:AAFFoUt8fKVdxPxAktSGwds_JRZnEfFIRxc/setWebhook?max_connections=50
		// {"ok":true,"result":true,"description":"Webhook is already deleted"}
		public function setWebhook( $url, $certificate = '', $max_connections = 40 ) {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			if ( $certificate == '' ) {
				$arguments = array( 'url' => $url, 'max_connections' => $max_connections );
			} 
			else {
				$arguments = array( 'url' => $url, 'certificate' => $certificate, 'max_connections' => $max_connections );
			}
			
			$result = $this->call( 'setWebhook', $arguments );
			
			if ( $result === true ) {
				$this->Webhook = true;
			}
			
			return $result;
		}


		// https://api.telegram.org/bot847811843:AAFFoUt8fKVdxPxAktSGwds_JRZnEfFIRxc/deleteWebhook
		// {"ok":true,"result":true,"description":"Webhook is already deleted"}
		public function deleteWebhook() {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			$result = $this->call( 'deleteWebhook' );
			
			if ( $result === true ) {
				$this->Webhook = false;
			}
			
			return $result;
		}


		// https://api.telegram.org/bot847811843:AAFFoUt8fKVdxPxAktSGwds_JRZnEfFIRxc/getWebhookInfo
		// {"ok":true,"result":{"url":"","has_custom_certificate":false,"pending_update_count":3}}
		// {"ok":true,"result":{"url":"https://mdlis.000webhostapp.com/api/telegramAAFFoUt8fKVdxPxAktSGwds_JRZnEfFIRxc.php","has_custom_certificate":false,"pending_update_count":0,"max_connections":40}}
		public function getWebhookInfo() {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			$result = $this->call( 'getWebhookInfo' );

			if ( !is_null( $result ) ) {
				if ( !empty( $result->result->url ) ) {
					$this->Webhook = true;
				}
			}
			
			return $result;
		}
	
	
		public function sendMessage( $chat_id, $text, array $options = array() ) {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			$arguments = array_merge( $options, array( 'chat_id' => $chat_id, 'text' => $text ) );

			return $this->call( 'sendMessage', $arguments );
		}
	
	
		public function editMessage( $chat_id, $message_id, $text, array $options = array() ) {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			$arguments = array_merge( $options, array( 'chat_id' => $chat_id, 'message_id' => $message_id, 'text' => $text ) );

			return $this->call( 'editMessageText', $arguments );
		}
	
	
		public function deleteMessage( $chat_id, $message_id ) {
			if ( is_null( $this->id ) ) {
				$this->lastErrorCode        = 404;
				$this->lastErrorDescription = 'Not found';

				return null;
			}
			
			$arguments = array( 'chat_id' => $chat_id, 'message_id' => $message_id );

			return $this->call( 'deleteMessage', $arguments );
		}
	}
?>