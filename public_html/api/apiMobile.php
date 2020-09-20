<?php
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


	class mobileUser {
		public $id                        = null;
		private $login                    = '';
		private $password                 = '';
		private $name                     = '';
		private $first_name               = '';
		private $middle_name              = '';
		private $last_name                = '';
		private $gender                   = 0;
		private $email                    = '';
		private $email_notifications      = 0;
		private $phone                    = '';
		private $phone_notifications      = 0;
		private $appeal_type              = 0;
		private $appeal                   = '';
		private $comment                  = '';
		private $consent_on_personal_data = 0;
		private $social_id                = 0;
		private $social_notifications     = 0;
		public $error_code                = 0;
		public $error                     = '';
		

		public function __construct( $id ) {
			global $mysqli;
			
			if ( isset( $id ) ) {
				$query = "SELECT * 
						  FROM Users
						  WHERE ( UserId = " . $id . " ) 
						  LIMIT 1";

				if ( $result = mysqli_query( $mysqli, $query ) ) { 
					if ( $row = mysqli_fetch_array( $result ) ) { 
						$this->id                       = $id;
						$this->login                    = trim( $row[ 'Login' ] );
						$this->password                 = trim( $row[ 'Password' ] );
						$this->name                     = trim( $row[ 'Name' ] );
						$this->gender                   = $row[ 'Gender' ];
						$this->email                    = trim( $row[ 'Email' ] );
						$this->email_notifications      = $row[ 'EmailNotifications' ];
						$this->phone                    = trim( $row[ 'Phone' ] );
						$this->phone_notifications      = $row[ 'PhoneNotifications' ];
						$this->appeal_type              = $row[ 'AppealType' ];
						$this->appeal                   = trim( $row[ 'Appeal' ] );
						$this->comment                  = trim( $row[ 'Comment' ] );
						$this->consent_on_personal_data = $row[ 'ConsentOnPersonalData' ];
						$this->social_id                = $row[ 'SocialId' ];
						$this->social_notifications     = $row[ 'SocialNotifications' ];
						
						$fullname          = explode( ' ', $this->name, 3 );
						$this->first_name  = ( ( count( $fullname ) >= 2 ) ? mb_word( $fullname[ 1 ], 'UTF-8' ) : $this->first_name );
						$this->middle_name = ( ( count( $fullname ) >= 3 ) ? mb_word( $fullname[ 2 ], 'UTF-8' ) : $this->middle_name );
						$this->last_name   = ( ( count( $fullname ) >= 1 ) ? mb_word( $fullname[ 0 ], 'UTF-8' ) : $this->last_name );
					} 

					$result->close(); 
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
				else if ( !empty( $this->login ) ) {
					$this->appeal = $this->login;
				}	
				else if ( empty( $this->appeal ) ) {
					$this->appeal = 'Гость';
				}
			}
		}
		
		
		public function call( $method, array $arguments = null ) { 
			$this->error_code = 0;
			$this->error      = '';
			
			if ( $method === 'authorization' ) {
				return $this->authorization( $arguments[ 'login' ], $arguments[ 'password' ] );
			}
			else if ( $method === 'getUserInfo' ) {
				return $this->getUserInfo();
			}
			else if ( $method === 'deleteUser' ) {
				return $this->deleteUser();
			}
			else if ( $method === 'updateUser' ) {
				return $this->updateUser( $arguments );
			}
			else if ( $method === 'getPersonalAccounts' ) {
				return $this->getPersonalAccounts( $arguments );
			}
			else if ( $method === 'getQueryAccounts' ) {
				return $this->getQueryAccounts( $arguments );
			}
			else if ( $method === 'getCompanyList' ) {
				return $this->getCompanyList();
			}
			else if ( $method === 'updateAccountQuery' ) {
				return $this->updateAccountQuery( $arguments );
			}
			else if ( $method === 'deleteUserAccount' ) {
				return $this->deleteUserAccount( $arguments );
			}
			else if ( $method === 'getAccountInfo' ) {
				return $this->getAccountInfo( $arguments );
			}
			else if ( $method === 'updateDeviceIndications' ) {
				return $this->updateDeviceIndications( $arguments );
			}
		
			$this->error_code = 404;
			$this->error      = 'Method not found.';
			
			return json_encode( [ 'ok'         => false,
								  'error_code' => $this->error_code,
								  'error'      => $this->error ], 
								JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
		}
		
		
		private function authorization( $login, $password ) {
			global $mysqli;
			
			if ( isset( $login ) ) {
				$query = "SELECT UserId, Password 
						  FROM Users
						  WHERE ( TRIM( Login ) = '" . $login . "' ) 
						  LIMIT 1";

				if ( $result = mysqli_query( $mysqli, $query ) ) { 
					if ( $row = mysqli_fetch_array( $result ) ) { 
						if ( password_verify( trim( $password ), trim( $row[ 'Password' ] ) ) ) {
							return json_encode( [ 'ok'         => true,
												  'error_code' => 0,
												  'error'      => '',
												  'data'       => [ 'id' => $row[ 'UserId' ] ] ], 
												JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
						}
					} 

					$result->close(); 
				}
				else {
					$this->error_code = mysqli_errno( $mysqli );
					$this->error      = mysqli_error( $mysqli );
					
					return json_encode( [ 'ok'         => false,
										  'error_code' => $this->error_code,
										  'error'      => $this->error ], 
										JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				}
			}	
				
			return json_encode( [ 'ok'         => false,
								  'error_code' => 403,
								  'error'      => 'Wrong login or password.' ], 
								JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
		}
		
		
		private function getUserInfo() {
			if ( is_null( $this->id ) ) {
				$this->error_code = 404;
				$this->error      = 'User not found.';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
			
			$result = json_encode( [ 'ok'         => true,
									 'error_code' => 0,
									 'error'      => '',
									 'data'       => [ 'id'                       => $this->id,
													   'login'                    => $this->login,
													   'password'                 => $this->password,
													   'name'                     => $this->name,
													   'first_name'               => $this->first_name,
													   'middle_name'              => $this->middle_name,
													   'last_name'                => $this->last_name,
													   'gender'                   => $this->gender,
													   'email'                    => $this->email,
													   'email_notifications'      => ( $this->email_notifications == 1 ),
													   'phone'                    => $this->phone,
													   'phone_notifications'      => ( $this->phone_notifications == 1 ),
													   'appeal_type'              => $this->appeal_type,
													   'appeal'                   => $this->appeal,
													   'comment'                  => $this->comment,
													   'consent_on_personal_data' => ( $this->consent_on_personal_data == 1 ),
													   'social_id'                => $this->social_id,
													   'social_notifications'     => ( $this->social_notifications == 1 ) ] ], 
								   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			
			if ( $result === false ) {
				$this->error_code = json_last_error();
				$this->error      = json_last_error_msg();
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
				
			return $result;
		}
		
		
		private function deleteUser() {
			global $mysqli;
			
			if ( is_null( $this->id ) ) {
				$this->error_code = 404;
				$this->error      = 'User not found.';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
			
			$query = "DELETE 
					  FROM Users 
					  WHERE ( UserId = " . $this->id . " )";
					        

			if ( $result = mysqli_query( $mysqli, $query ) ) { 
				return json_encode( [ 'ok'         => true,
									  'error_code' => 0,
									  'error'      => '' ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			
			}
			
			$this->error_code = mysqli_errno( $mysqli );
			$this->error      = mysqli_error( $mysqli );
			
			return json_encode( [ 'ok'         => false,
								  'error_code' => $this->error_code,
								  'error'      => $this->error ], 
								JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
		}
		
		
		private function updateUser( $arguments ) {
			global $mysqli;
			
			$this->login                    = ( isset( $arguments[ 'login' ] ) ? trim( $arguments[ 'login' ] ) : $this->login );
			$this->password                 = ( isset( $arguments[ 'password' ] ) ? trim( $arguments[ 'password' ] ) : $this->password );
			$this->name                     = ( isset( $arguments[ 'name' ] ) ? trim( $arguments[ 'name' ] ) : $this->name );
			$this->gender                   = ( isset( $arguments[ 'gender' ] ) ? trim( $arguments[ 'gender' ] ) : $this->gender );
			$this->email                    = ( isset( $arguments[ 'email' ] ) ? trim( $arguments[ 'email' ] ) : $this->email );
			$this->email_notifications      = ( isset( $arguments[ 'email_notifications' ] ) ? trim( $arguments[ 'email_notifications' ] ) : $this->email_notifications );
			$this->phone                    = ( isset( $arguments[ 'phone' ] ) ? trim( $arguments[ 'phone' ] ) : $this->phone );
			$this->phone_notifications      = ( isset( $arguments[ 'phone_notifications' ] ) ? trim( $arguments[ 'phone_notifications' ] ) : $this->phone_notifications );
			$this->appeal_type              = ( isset( $arguments[ 'appeal_type' ] ) ? trim( $arguments[ 'appeal_type' ] ) : $this->appeal_type );
			$this->appeal                   = ( isset( $arguments[ 'appeal' ] ) ? trim( $arguments[ 'appeal' ] ) : $this->appeal );
			$this->comment                  = ( isset( $arguments[ 'comment' ] ) ? trim( $arguments[ 'comment' ] ) : $this->comment );
			$this->consent_on_personal_data = ( isset( $arguments[ 'consent_on_personal_data' ] ) ? trim( $arguments[ 'consent_on_personal_data' ] ) : $this->consent_on_personal_data );
			$this->social_id                = ( isset( $arguments[ 'social_id' ] ) ? trim( $arguments[ 'social_id' ] ) : $this->social_id );
			$this->social_notifications     = ( isset( $arguments[ 'social_notifications' ] ) ? trim( $arguments[ 'social_notifications' ] ) : $this->social_notifications );
			
			if ( is_null( $this->id ) ) {
				if ( ( empty( $this->login ) ) || ( empty( $this->password ) ) ) {
					$this->error_code = 403;
					$this->error      = 'Не указан логин или пароль нового пользователя.';
					
					return json_encode( [ 'ok'         => false,
										  'error_code' => $this->error_code,
										  'error'      => $this->error ], 
										JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				}
				
				$query = "SELECT UserId 
						  FROM Users
						  WHERE ( TRIM( Login ) = '" . $this->login . "' ) 
						  LIMIT 1";

				if ( $result = mysqli_query( $mysqli, $query ) ) { 
					if ( $row = mysqli_fetch_array( $result ) ) { 
						$this->error_code = 403;
						$this->error      = 'Пользователь с указанным логином уже существует.';
						
						return json_encode( [ 'ok'         => false,
											  'error_code' => $this->error_code,
											  'error'      => $this->error ], 
											JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
					} 
				}
				else {
					$this->error_code = mysqli_errno( $mysqli );
					$this->error      = mysqli_error( $mysqli );
					
					return json_encode( [ 'ok'         => false,
										  'error_code' => $this->error_code,
										  'error'      => $this->error ], 
										JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				}
								
				$query = "INSERT INTO Users ( Login, " . ( isset( $arguments[ 'password' ] ) ? "Password, " : "" ) . "Name, Gender, Email, EmailNotifications, Phone, PhoneNotifications, AppealType, Appeal, Comment, ConsentOnPersonalData, SocialId, SocialNotifications, RegistrationDate ) 
						  VALUES ( '" . $this->login . "', " . ( isset( $arguments[ 'password' ] ) ? ( "'" . password_hash( $this->password, PASSWORD_DEFAULT ) . "', " ) : "" ) . "'" . $this->name . "', " . $this->gender . ", '" . $this->email . "', " . $this->email_notifications . ", '" . $this->phone . "', " . $this->phone_notifications . ", " . $this->appeal_type . ", '" . $this->appeal . "', '" . $this->comment . "', " . $this->consent_on_personal_data . ", " . $this->social_id . ", " . $this->social_notifications . ", NOW() )";

				if ( $result = mysqli_query( $mysqli, $query ) ) { 
					return json_encode( [ 'ok'         => true,
										  'error_code' => 0,
										  'error'      => '',
										  'data'       => [ 'id' => mysqli_insert_id ( $mysqli ) ] ], 
										JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				}

			}
			else {
				$query = "UPDATE Users 
						  SET Login = '" . $this->login . "', " . ( isset( $arguments[ 'password' ] ) ? ( "Password = '" . password_hash( $this->password, PASSWORD_DEFAULT ) . "', " ) : "" ) . "Name = '" . $this->name . "', Gender = " . $this->gender . ", EMail = '" . $this->email . "', EmailNotifications = " . $this->email_notifications . ", Phone = '" . $this->phone . "', PhoneNotifications = " . $this->phone_notifications . ", AppealType = " . $this->appeal_type . ", Appeal = '" . $this->appeal . "', Comment = '" . $this->comment . "', ConsentOnPersonalData = 1, SocialId = " . $this->social_id . ", SocialNotifications = " . $this->social_notifications . ", RegistrationDate = NOW() 
						  WHERE ( UserId = " . $this->id . " )";

				if ( $result = mysqli_query( $mysqli, $query ) ) { 
					return json_encode( [ 'ok'         => true,
										  'error_code' => 0,
										  'error'      => '',
										  'data'       => [ 'id' => $this->id ] ], 
										JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
				
				}
			}
			
			$this->error_code = mysqli_errno( $mysqli );
			$this->error      = mysqli_error( $mysqli );
			
			return json_encode( [ 'ok'         => false,
								  'error_code' => $this->error_code,
								  'error'      => $this->error ], 
								JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
		}
	
	
		private function getPersonalAccounts( $arguments ) {
			global $mysqli;
			
			if ( is_null( $this->id ) ) {
				$this->error_code = 404;
				$this->error      = 'User not found.';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
			
			$data = commonGetPersonalAccounts( $this->id, true );

			if ( $data === false ) { 
				$this->error_code = mysqli_errno( $mysqli );
				$this->error      = mysqli_error( $mysqli );
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			$result = json_encode( [ 'ok'         => true,
									 'error_code' => 0,
									 'error'      => '',
									 'data'       => $data ], 
								   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			
			if ( $result === false ) {
				$this->error_code = json_last_error();
				$this->error      = json_last_error_msg();
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
				
			return $result;
		}
		
		
		private function getQueryAccounts( $arguments ) {
			global $mysqli;
			
			if ( is_null( $this->id ) ) {
				$this->error_code = 404;
				$this->error      = 'User not found.';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
			
			$status = ( isset( $arguments[ 'status' ] ) ? $arguments[ 'status' ] : null );
			$data   = commonGetQueryAccounts( $this->id, $status );

			if ( $data === false ) { 
				$this->error_code = mysqli_errno( $mysqli );
				$this->error      = mysqli_error( $mysqli );
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			$result = json_encode( [ 'ok'         => true,
									 'error_code' => 0,
									 'error'      => '',
									 'data'       => $data ], 
								   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			
			if ( $result === false ) {
				$this->error_code = json_last_error();
				$this->error      = json_last_error_msg();
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
				
			return $result;
		}
		
		
		private function getCompanyList() {
			global $mysqli;
			
			$data = commonGetCompanyList();

			if ( $data === false ) { 
				$this->error_code = mysqli_errno( $mysqli );
				$this->error      = mysqli_error( $mysqli );
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			$result = json_encode( [ 'ok'         => true,
									 'error_code' => 0,
									 'error'      => '',
									 'data'       => $data ], 
								   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			
			if ( $result === false ) {
				$this->error_code = json_last_error();
				$this->error      = json_last_error_msg();
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
				
			return $result;
		}
	
		
		private function updateAccountQuery( $arguments ) {
			global $mysqli;
			
			$account_name = ( isset( $arguments[ 'account_name' ] ) ? trim( $arguments[ 'account_name' ] ) : '' );
			$company_id   = ( isset( $arguments[ 'company_id' ] ) ? $arguments[ 'company_id' ] : null );
			
			if ( is_null( $this->id ) ) {
				$this->error_code = 404;
				$this->error      = 'User not found.';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			if ( is_null( $company_id ) ) {
				$this->error_code = 404;
				$this->error      = 'Не указано значение "Управляющая компания".';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			$query = "INSERT INTO AccountsQuery ( AccountName, UserId, CompanyId, ObjectId, QueryDate, QueryStatus, QueryAnswer ) 
					  VALUES ( '" . $account_name . "', " . $this->id . ", " . $company_id . ", 0, NOW(), 0, '' )";

			if ( $result = mysqli_query( $mysqli, $query ) ) { 
				return json_encode( [ 'ok'         => true,
									  'error_code' => 0,
									  'error'      => '' ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
			
			$this->error_code = mysqli_errno( $mysqli );
			$this->error      = mysqli_error( $mysqli );
			
			return json_encode( [ 'ok'         => false,
								  'error_code' => $this->error_code,
								  'error'      => $this->error ], 
								JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
		}
	
		
		private function deleteUserAccount( $arguments ) {
			global $mysqli;
			
			if ( is_null( $this->id ) ) {
				$this->error_code = 404;
				$this->error      = 'User not found.';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
			
			$account_id = ( isset( $arguments[ 'account_id' ] ) ? $arguments[ 'account_id' ] : null );
			$result     = commonDeleteAccountUser( $this->id, $account_id );
			
			if ( $result === false ) { 
				$this->error_code = mysqli_errno( $mysqli );
				$this->error      = mysqli_error( $mysqli );
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			$result = json_encode( [ 'ok'         => true,
									 'error_code' => 0,
									 'error'      => '',
									 'data'       => $data ], 
								   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			
			if ( $result === false ) {
				$this->error_code = json_last_error();
				$this->error      = json_last_error_msg();
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
				
			return $result;
		}
	
	
		private function getAccountInfo( $arguments ) {
			global $mysqli;
			
			if ( is_null( $this->id ) ) {
				$this->error_code = 404;
				$this->error      = 'User not found.';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
			
			$account_id       = ( isset( $arguments[ 'account_id' ] ) ? $arguments[ 'account_id' ] : null );
			$service_id       = ( isset( $arguments[ 'service_id' ] ) ? $arguments[ 'service_id' ] : null );
			$tariff_id        = ( isset( $arguments[ 'tariff_id' ] ) ? $arguments[ 'tariff_id' ] : null );
			$calculation_type = ( isset( $arguments[ 'calculation_type' ] ) ? $arguments[ 'calculation_type' ] : null );
			$data             = commonGetAccountInfo( $this->id, $account_id, $service_id, $tariff_id, $calculation_type );
			
			if ( $data === false ) { 
				$this->error_code = mysqli_errno( $mysqli );
				$this->error      = mysqli_error( $mysqli );
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			$result = json_encode( [ 'ok'         => true,
									 'error_code' => 0,
									 'error'      => '',
									 'data'       => $data ], 
								   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			
			if ( $result === false ) {
				$this->error_code = json_last_error();
				$this->error      = json_last_error_msg();
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			return $result;
		}
		
		
		private function updateDeviceIndications( $arguments ) {
			global $mysqli;
			
			$device_id   = ( isset( $arguments[ 'device_id' ] ) ? $arguments[ 'device_id' ] : null );
			$indications = ( isset( $arguments[ 'indications' ] ) ? $arguments[ 'indications' ] : null );
			
			if ( is_null( $device_id ) ) {
				$this->error_code = 404;
				$this->error      = 'Не указано значение "Прибор учета".';
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
								    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}

			$result = commonUpdateDeviceIndications( $device_id, $indications );

			if ( $result === false ) { 
				$this->error_code = mysqli_errno( $mysqli );
				$this->error      = mysqli_error( $mysqli );
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
			
			$result = json_encode( [ 'ok'         => true,
									 'error_code' => 0,
									 'error'      => '' ], 
								   JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			
			if ( $result === false ) {
				$this->error_code = json_last_error();
				$this->error      = json_last_error_msg();
				
				return json_encode( [ 'ok'         => false,
									  'error_code' => $this->error_code,
									  'error'      => $this->error ], 
									JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
			}
				
			return $result;
		}
	}
?>