<?php
	include_once $_SERVER[ 'DOCUMENT_ROOT' ] . "/api/_connection.php";
	

	function commonGetUserInfo( $user_id ) {
		global $mysqli;
		
		$query  = "SELECT 
					   Users.UserId AS UserId,
					   Users.Login AS Login,
					   Users.Password AS Password,
					   Users.Name AS Name,
					   Users.Gender AS Gender,
					   Users.Email AS Email,
					   Users.EmailNotifications AS EmailNotifications,
					   Users.Phone AS Phone,
					   Users.PhoneNotifications AS PhoneNotifications,
					   Users.AppealType AS AppealType,
					   Users.Appeal AS Appeal,
					   Users.Comment AS Comment,
					   Users.ConsentOnPersonalData AS ConsentOnPersonalData,
					   Users.BotId AS BotId,
					   IFNULL( Bots.BotType, 0 ) AS BotType,
					   IFNULL( Bots.SocialId, 0 ) AS BotSocialId,
					   IFNULL( Bots.Token, '' ) AS BotToken,
					   Users.SocialId AS SocialId,
					   Users.SocialNotifications AS SocialNotifications,
					   Users.RegistrationDate AS RegistrationDate
				   FROM Users AS Users 
				   LEFT JOIN Bots AS Bots ON ( Bots.BotId = Users.BotId ) 
				   WHERE ( Users.UserId = " . $user_id . " )
				   LIMIT 1";

		if ( $result = mysqli_query( $mysqli, $query ) ) { 
			if ( $row = mysqli_fetch_array( $result ) ) { 
				$data = array( 'UserId'                => $row[ 'UserId' ], 
							   'Login'                 => $row[ 'Login' ], 
							   'Password'              => $row[ 'Password' ], 
							   'Name'                  => $row[ 'Name' ], 
							   'Gender'                => $row[ 'Gender' ], 
							   'Email'                 => $row[ 'Email' ], 
							   'EmailNotifications'    => $row[ 'EmailNotifications' ], 
							   'Phone'                 => $row[ 'Phone' ], 
							   'PhoneNotifications'    => $row[ 'PhoneNotifications' ], 
							   'AppealType'            => $row[ 'AppealType' ], 
							   'Appeal'                => $row[ 'Appeal' ], 
							   'Comment'               => $row[ 'Comment' ], 
							   'ConsentOnPersonalData' => $row[ 'ConsentOnPersonalData' ], 
							   'BotId'                 => $row[ 'BotId' ], 
							   'BotType'               => $row[ 'BotType' ], 
							   'BotSocialId'           => $row[ 'BotSocialId' ], 
							   'BotToken'              => $row[ 'BotToken' ], 
							   'SocialId'              => $row[ 'SocialId' ], 
							   'SocialNotifications'   => $row[ 'SocialNotifications' ], 
							   'RegistrationDate'      => $row[ 'RegistrationDate' ] );
			} 

			$result->close();
			return $data;
		}
			
		return false;
	}
	
	
	function commonGetPersonalAccounts( $user_id, $is_using = null ) {
		global $mysqli;
		
		$query = "SELECT PersonalAccounts.AccountId AS AccountId,
						 PersonalAccounts.Name AS AccountName,
						 IFNULL( Abonents.Name, '' ) AS AbonentName,
						 IFNULL( Objects.Name, '' ) AS ObjectName,
						 IFNULL( Objects.KadastrNo, '' ) AS ObjectKadastrNo,
						 IFNULL( Objects.Address, '' ) AS ObjectAddress,
						 IFNULL( ManagementCompany.Name, '' ) AS CompanyName,
						 PersonalAccounts.StartDate AS StartDate,
						 PersonalAccounts.EndDate AS EndDate,
						 PersonalAccounts.Using AS IsUsing
				  FROM AccountUsers AS AccountUsers
				  LEFT JOIN PersonalAccounts AS PersonalAccounts ON ( PersonalAccounts.AccountId = AccountUsers.AccountId )
				  LEFT JOIN Abonents AS Abonents ON ( Abonents.AbonentId = PersonalAccounts.AbonentId )
				  LEFT JOIN Objects AS Objects ON ( Objects.ObjectId = PersonalAccounts.ObjectId )
				  LEFT JOIN ManagementCompany AS ManagementCompany ON ( ManagementCompany.CompanyId = PersonalAccounts.CompanyId )
				  WHERE ( AccountUsers.UserId = " . $user_id . " ) AND
						( IFNULL( AccountUsers.Active, 0 ) = 1 ) AND
						( PersonalAccounts.AccountId IS NOT NULL ) 
						" . ( is_null( $is_using ) ? " AND ( PersonalAccounts.Using = " . ( $is_using ? "1" : "0" ) . " )" : "" ) . " 
				  ORDER BY PersonalAccounts.Name";

		if ( $result = mysqli_query( $mysqli, $query ) ) { 
			while ( $row = mysqli_fetch_array( $result ) ) { 
				$data[] = [ 'account_id'        => $row[ 'AccountId' ],
							'account_name'      => $row[ 'AccountName' ],
							'abonent_name'      => $row[ 'AbonentName' ],
							'object_name'       => $row[ 'ObjectName' ],
							'object_kadastr_no' => $row[ 'ObjectKadastrNo' ],
							'object_address'    => $row[ 'ObjectAddress' ],
							'company_name'      => $row[ 'CompanyName' ],
							'start_date'        => $row[ 'StartDate' ],
							'end_date'          => $row[ 'EndDate' ],
							'is_using'          => ( $row[ 'IsUsing' ] == 1 ) ];
			} 

			$result->close();
			return $data;
		}
		
		return false;
	}


	function commonGetQueryAccounts( $user_id, $status = null ) {
		global $mysqli;
		
		$query = "SELECT AccountsQuery.QueryId AS QueryId,
						 DATE_FORMAT( AccountsQuery.QueryDate, '%Y-%m-%d' ) AS QueryDate,
						 AccountsQuery.QueryStatus AS QueryStatus,
						 AccountsQuery.QueryAnswer AS QueryAnswer,
						 AccountsQuery.AccountName AS AccountName,
						 AccountsQuery.CompanyId AS CompanyId,
						 IFNULL( ManagementCompany.Name, '' ) AS CompanyName,
						 AccountsQuery.ObjectId AS ObjectId,
						 IFNULL( Objects.Name, '' ) AS ObjectName,
						 IFNULL( Objects.KadastrNo, '' ) AS ObjectKadastrNo,
						 IFNULL( Objects.Address, '' ) AS ObjectAddress
				  FROM AccountsQuery AS AccountsQuery
				  LEFT JOIN ManagementCompany AS ManagementCompany ON ( ManagementCompany.CompanyId = AccountsQuery.CompanyId )
				  LEFT JOIN Objects AS Objects ON ( Objects.ObjectId = AccountsQuery.ObjectId )
				  WHERE ( AccountsQuery.UserId = " . $user_id . " )";
				  
		if ( !is_null( $status ) ) {
			if ( $status === true ) {
				$query = $query . " AND ( AccountsQuery.QueryStatus = 1 )";
			}
			else if ( $status === false ) {
				$query = $query . " AND ( AccountsQuery.QueryStatus <> 1 )";
			}
			else {
				$query = $query . " AND ( AccountsQuery.QueryStatus = " . $status . " )";
			}
		}
						
		$query = $query . "		  
						   ORDER BY AccountsQuery.QueryDate";

		if ( $result = mysqli_query( $mysqli, $query ) ) { 
			while ( $row = mysqli_fetch_array( $result ) ) { 
				$data[] = [ 'query'   => [ 'id'          => $row[ 'QueryId' ],
										   'date'        => $row[ 'QueryDate' ],
										   'text'        => $row[ 'AccountName' ],
										   'status'      => $row[ 'QueryStatus' ],
										   'status_name' => ( ( $row[ 'QueryStatus' ] == 0 ) ? 'На рассмотрении' : ( ( $row[ 'QueryStatus' ] == 1 ) ? 'Одобрено' : 'Отказано' ) ),
										   'answer'      => $row[ 'QueryAnswer' ] ],
							'company' => [ 'id'   => $row[ 'CompanyId' ],
										   'name' => $row[ 'CompanyName' ] ],
							'object'  => [ 'id'         => $row[ 'ObjectId' ],
										   'name'       => $row[ 'ObjectName' ],
										   'kadastr_no' => $row[ 'ObjectKadastrNo' ],
										   'address'    => $row[ 'ObjectAddress' ] ] ];
			} 

			$result->close();
			return $data;
		}
		
		return false;
	}


	function commonGetCompanyList( $filter = '' ) {
		global $mysqli;

		if ( !empty( $filter ) ) {
			$filter = "WHERE ( UCASE( ManagementCompany.Name ) LIKE '" . $filter . "%' )";
		}
		
		$query = "SELECT 
					  ManagementCompany.CompanyId AS CompanyId, 
					  ManagementCompany.Name AS Name, 
					  ManagementCompany.FullName AS FullName 
				  FROM ManagementCompany AS ManagementCompany 
				  " . $filter . " 
				  ORDER BY ManagementCompany.Name";

		if ( $result = mysqli_query( $mysqli, $query ) ) { 
			while ( $row = mysqli_fetch_array( $result ) ) { 
				$data[] = [ 'id'        => ( $full_list ? null : $row[ 'CompanyId' ] ),
							'name'      => $row[ 'Name' ],
							'full_name' => ( $full_list ? $row[ 'FullName' ] : '' ) ];
			} 

			$result->close();
			return $data;
		}
		
		return false;
	}


	function commonGetCompanyAlphabet() {
		global $mysqli;

		$query = "SELECT DISTINCT 
					  UCASE( LEFT( ManagementCompany.Name, 1 ) ) AS Letter 
				  FROM ManagementCompany AS ManagementCompany 
				  ORDER BY UCASE( LEFT( ManagementCompany.Name, 1 ) )";

		if ( $result = mysqli_query( $mysqli, $query ) ) { 
			while ( $row = mysqli_fetch_array( $result ) ) { 
				$data[] = $row[ 'Letter' ];
			} 

			$result->close();
			return $data;
		}

		return false;
	}

	
	function commonGetCompanyName( $company_id ) {
		global $mysqli;

		$query = "SELECT 
					  ManagementCompany.Name AS Name 
				  FROM ManagementCompany AS ManagementCompany 
				  WHERE ( ManagementCompany.CompanyId = " . $company_id . " ) 
				  LIMIT 1";

		if ( $result = mysqli_query( $mysqli, $query ) ) { 
			if ( $row = mysqli_fetch_array( $result ) ) { 
				return $row[ 'Name' ];
			} 
		}
		
		return '';
	}


	function commonUpdateAccountQuery( $user_id, $field, $value ) {
		global $mysqli;

		$update = false;
		$query  = "SELECT 
					   AccountsQuery.QueryId AS QueryId 
				   FROM AccountsQuery AS AccountsQuery 
				   WHERE ( AccountsQuery.UserId = " . $user_id . " ) AND 
				         ( AccountsQuery.QueryStatus = 9 ) 
				   LIMIT 1";

		if ( $result = mysqli_query( $mysqli, $query ) ) { 
			if ( $row = mysqli_fetch_array( $result ) ) { 
				$update = true;
			} 
		}
		
		if ( !$update ) {
			$query = "INSERT INTO AccountsQuery ( AccountName, UserId, CompanyId, ObjectId, QueryDate, QueryStatus, QueryAnswer ) 
					  VALUES ( '', " . $user_id . ", 0, 0, NOW(), 9, '' )";

			if ( mysqli_query( $mysqli, $query ) === false ) {
				return false;
			}
		}

		if ( ( $field == 'QueryStatus' ) && ( $value == -1 ) ) {
			$query = "DELETE 
					  FROM AccountsQuery 
					  WHERE ( UserId = " . $user_id . " ) AND 
						    ( QueryStatus = 9 )";
		}
		else {
			$query = "UPDATE AccountsQuery 
					  SET QueryDate = NOW(), ";
						
			if ( $field == 'AccountName' ) {
				$query = $query . $field . " = '" . ( ( $value == "/" ) ? "" : $value ) . "' ";
			}
			else {
				$query = $query . $field . " = " . $value . " ";
			}
				
			$query = $query . "
							   WHERE ( UserId = " . $user_id . " ) AND 
									 ( QueryStatus = 9 )";
		}

		return mysqli_query( $mysqli, $query );
	}


	function commonDeleteAccountUser( $user_id, $account_id ) {
		global $mysqli;

		$query = "DELETE 
				  FROM AccountUsers
				  WHERE ( UserId = " . $user_id . " ) AND 
						( AccountId = " . $account_id . " )";

		return mysqli_query( $mysqli, $query );
	}


	function commonGetAccountInfo( $user_id, $account_id, $service_id = null, $tariff_id = null, $calculation_type = null ) {
		global $mysqli;
		
		$service_where          = '';
		$tariff_where           = '';
		$calculation_type_where = '';
		
		if ( !is_null( $service_id ) ) {
			$service_where = " AND Alias.ServiceId = " . $service_id;
		}

		if ( !is_null( $tariff_id ) ) {
			$tariff_where = " AND Alias.TariffId = " . $tariff_id;
		}

		if ( !is_null( $calculation_type ) ) {
			$calculation_type_where = " AND Alias.CalculationType = " . $calculation_type;
		}

		$query = "SET @user_id := " . $user_id . ";
                  SET @account_id := " . $account_id . ";

                  SELECT
                      @parent_id := Objects.ParentId AS ObjectId
                  FROM PersonalAccounts AS PersonalAccounts
                  LEFT JOIN Objects AS Objects ON 
                      ( Objects.ObjectId = PersonalAccounts.ObjectId )
                  WHERE
                      ( PersonalAccounts.AccountId = @account_id ) AND
                      ( PersonalAccounts.`Using` = 1 );

                  CREATE TEMPORARY TABLE tempObjects
                      SELECT
                          PersonalAccounts.AccountId AS AccountId,
                          Objects.ObjectId AS ObjectId,
                          IFNULL( Objects.ObjectType, 0 ) AS ObjectType,
                          IFNULL( Objects.Square, 0 ) AS Square
                      FROM Objects AS Objects
                      LEFT JOIN PersonalAccounts AS PersonalAccounts ON 
                          ( PersonalAccounts.ObjectId = Objects.ObjectId )
                      WHERE
                          ( IFNULL( PersonalAccounts.`Using`, 1 ) = 1 ) AND
                          ( @parent_id IS NOT NULL ) AND
                          ( Objects.ParentId = @parent_id );

                  CREATE TEMPORARY TABLE tempAccountNormatives
                      SELECT 
                          AccountNormatives.AccountId AS AccountId,
                          AccountNormatives.ServiceId AS ServiceId,
                          AccountNormatives.TariffId AS TariffId,
                          IFNULL( AccountNormatives.Count, 0 ) AS Count
                      FROM 
                          AccountNormatives AS AccountNormatives
                      RIGHT JOIN ( SELECT 
                                       AccountNormatives.AccountId AS AccountId,
                                       AccountNormatives.ServiceId AS ServiceId,
                                       AccountNormatives.TariffId AS TariffId,
                                       MAX( AccountNormatives.Date ) AS Date
                                   FROM 
                                       AccountNormatives AS AccountNormatives
                                   WHERE 
                                       ( AccountNormatives.AccountId = @account_id )
									   " . str_replace( 'Alias.', 'AccountNormatives.', $service_where ) . "
									   " . str_replace( 'Alias.', 'AccountNormatives.', $tariff_where ) . "
                                   GROUP BY
                                       AccountNormatives.AccountId,
                                       AccountNormatives.ServiceId,
                                       AccountNormatives.TariffId ) AS AccountNormativesMax ON 
                          ( AccountNormativesMax.AccountId = AccountNormatives.AccountId ) AND
                          ( AccountNormativesMax.ServiceId = AccountNormatives.ServiceId ) AND
                          ( AccountNormativesMax.TariffId = AccountNormatives.TariffId ) AND
                          ( AccountNormativesMax.Date = AccountNormatives.Date )
                      WHERE 
                          ( AccountNormatives.AccountId = @account_id ) AND
                          ( AccountNormatives.`Using` = 1 )
						  " . str_replace( 'Alias.', 'AccountNormatives.', $service_where ) . "
						  " . str_replace( 'Alias.', 'AccountNormatives.', $tariff_where ) . ";

                  CREATE TEMPORARY TABLE tempAccountDevices
                      SELECT 
                          AccountDevices.AccountId AS AccountId,
                          AccountDevices.ServiceId AS ServiceId,
                          AccountDevices.TariffId AS TariffId,
                          AccountDevices.DeviceId AS DeviceId
                      FROM 
                          AccountDevices AS AccountDevices
                      RIGHT JOIN ( SELECT 
                                       AccountDevices.AccountId AS AccountId,
                                       AccountDevices.ServiceId AS ServiceId,
                                       AccountDevices.TariffId AS TariffId,
                                       AccountDevices.DeviceId AS DeviceId,
                                       MAX( AccountDevices.Date ) AS Date
                                   FROM 
                                       AccountDevices AS AccountDevices
                                   WHERE 
                                       ( ( AccountDevices.AccountId = @account_id ) OR
									     ( AccountDevices.AccountId IN ( SELECT 
                                                                             tempObjects.AccountId AS AccountID 
                                                                         FROM 
                                                                             tempObjects AS tempObjects ) ) ) 
									   " . str_replace( 'Alias.', 'AccountDevices.', $service_where ) . "
									   " . str_replace( 'Alias.', 'AccountDevices.', $tariff_where ) . "
                                   GROUP BY
                                       AccountDevices.AccountId,
                                       AccountDevices.ServiceId,
                                       AccountDevices.TariffId,
                                       AccountDevices.DeviceId ) AS AccountDevicesMax ON 
                          ( AccountDevicesMax.AccountId = AccountDevices.AccountId ) AND
                          ( AccountDevicesMax.ServiceId = AccountDevices.ServiceId ) AND
                          ( AccountDevicesMax.TariffId = AccountDevices.TariffId ) AND
                          ( AccountDevicesMax.DeviceId = AccountDevices.DeviceId ) AND
                          ( AccountDevicesMax.Date = AccountDevices.Date )
                      WHERE 
                          ( ( AccountDevices.AccountId = @account_id ) OR
						    ( AccountDevices.AccountId IN ( SELECT 
                                                                tempObjects.AccountId AS AccountID 
                                                            FROM 
                                                                tempObjects AS tempObjects ) ) ) AND
                          ( AccountDevices.`Using` = 1 )
						  " . str_replace( 'Alias.', 'AccountDevices.', $service_where ) . "
						  " . str_replace( 'Alias.', 'AccountDevices.', $tariff_where ) . ";

                  CREATE TEMPORARY TABLE tempDeviceIndications
                      SELECT 
                          DeviceIndications.DeviceId AS DeviceId,
                          SUM( DeviceIndications.PreviousIndications ) AS PreviousIndications,
                          SUM( DeviceIndications.CurrentIndications ) AS CurrentIndications,
                          SUM( DeviceIndications.CurrentIndications ) - SUM( DeviceIndications.PreviousIndications ) AS Indications
                      FROM ( SELECT 
                                 DeviceIndications.DeviceId AS DeviceId,
                                 IFNULL( DeviceIndications.Indications, 0 ) AS PreviousIndications,
                                 0 AS CurrentIndications
                             FROM 
                                 DeviceIndications AS DeviceIndications
                             RIGHT JOIN ( SELECT 
                                              DeviceIndications.DeviceId AS DeviceId,
                                              MAX( DeviceIndications.Date ) AS Date
                                          FROM 
                                              DeviceIndications AS DeviceIndications
                                          WHERE 
                                              ( DeviceIndications.DeviceId IN ( SELECT 
                                                                                    tempAccountDevices.DeviceId AS DeviceID 
                                                                                FROM 
                                                                                    tempAccountDevices AS tempAccountDevices ) ) AND
                                              ( DeviceIndications.Fixed = 1 )
                                          GROUP BY
                                              DeviceIndications.DeviceId ) AS DeviceIndicationsMax ON 
                                  ( DeviceIndicationsMax.DeviceId = DeviceIndications.DeviceId ) AND
                                  ( DeviceIndicationsMax.Date = DeviceIndications.Date )
                             WHERE 
                                 ( DeviceIndications.DeviceId IN ( SELECT 
                                                                       tempAccountDevices.DeviceId AS DeviceID 
                                                                   FROM 
                                                                       tempAccountDevices AS tempAccountDevices ) ) AND
                                 ( DeviceIndications.Fixed = 1 )

                             UNION ALL

                             SELECT 
                                 DeviceIndications.DeviceId AS DeviceId,
                                 0 AS PreviousIndications,
                                 IFNULL( DeviceIndications.Indications, 0 ) AS CurrentIndications
                             FROM 
                                 DeviceIndications AS DeviceIndications
                             RIGHT JOIN ( SELECT 
                                              DeviceIndications.DeviceId AS DeviceId,
                                              MAX( DeviceIndications.Date ) AS Date
                                          FROM 
                                              DeviceIndications AS DeviceIndications
                                          WHERE 
                                              ( DeviceIndications.DeviceId IN ( SELECT 
                                                                                    tempAccountDevices.DeviceId AS DeviceID 
                                                                                FROM 
                                                                                    tempAccountDevices AS tempAccountDevices ) ) AND
                                              ( DeviceIndications.Fixed = 0 )
                                          GROUP BY
                                              DeviceIndications.DeviceId ) AS DeviceIndicationsMax ON 
                                 ( DeviceIndicationsMax.DeviceId = DeviceIndications.DeviceId ) AND
                                 ( DeviceIndicationsMax.Date = DeviceIndications.Date )
                             WHERE 
                                 ( DeviceIndications.DeviceId IN ( SELECT 
                                                                       tempAccountDevices.DeviceId AS DeviceID 
                                                                   FROM 
                                                                       tempAccountDevices AS tempAccountDevices ) ) AND
                                 ( DeviceIndications.Fixed = 0 )
                      ) AS DeviceIndications
                      GROUP BY
                          DeviceIndications.DeviceId;

                  CREATE TEMPORARY TABLE tempAccountDevicesWithIndications
                      SELECT 
                          tempAccountDevices.AccountId AS AccountId,
                          tempAccountDevices.ServiceId AS ServiceId,
                          tempAccountDevices.TariffId AS TariffId,
                          tempAccountDevices.DeviceId AS DeviceId,
                          tempDeviceIndications.PreviousIndications AS PreviousIndications,
                          tempDeviceIndications.CurrentIndications AS CurrentIndications,
                          tempDeviceIndications.Indications AS Indications
                      FROM 
                          tempAccountDevices AS tempAccountDevices
                      LEFT JOIN tempDeviceIndications AS tempDeviceIndications ON 
                          ( tempDeviceIndications.DeviceId = tempAccountDevices.DeviceId );

                  CREATE TEMPORARY TABLE tempCommonDevices
                      SELECT 
                          CommonDevices.ObjectId AS ObjectId,
                          CommonDevices.ServiceId AS ServiceId,
                          CommonDevices.TariffId AS TariffId,
                          CommonDevices.DeviceId AS DeviceId
                      FROM 
                          CommonDevices AS CommonDevices
                      RIGHT JOIN ( SELECT 
                                       CommonDevices.ObjectId AS ObjectId,
                                       CommonDevices.ServiceId AS ServiceId,
                                       CommonDevices.TariffId AS TariffId,
                                       CommonDevices.DeviceId AS DeviceId,
                                       MAX( CommonDevices.Date ) AS Date
                                   FROM 
                                       CommonDevices AS CommonDevices
                                   WHERE 
                                       ( CommonDevices.ObjectId = @parent_id )
									   " . str_replace( 'Alias.', 'CommonDevices.', $service_where ) . "
									   " . str_replace( 'Alias.', 'CommonDevices.', $tariff_where ) . "
                                   GROUP BY
                                       CommonDevices.ObjectId,
                                       CommonDevices.ServiceId,
                                       CommonDevices.TariffId,
                                       CommonDevices.DeviceId ) AS CommonDevicesMax ON 
                          ( CommonDevicesMax.ObjectId = CommonDevices.ObjectId ) AND
                          ( CommonDevicesMax.ServiceId = CommonDevices.ServiceId ) AND
                          ( CommonDevicesMax.TariffId = CommonDevices.TariffId ) AND
                          ( CommonDevicesMax.DeviceId = CommonDevices.DeviceId ) AND
                          ( CommonDevicesMax.Date = CommonDevices.Date )
                      WHERE 
					      ( CommonDevices.ObjectId = @parent_id ) AND
                          ( CommonDevices.`Using` = 1 )
						  " . str_replace( 'Alias.', 'CommonDevices.', $service_where ) . "
						  " . str_replace( 'Alias.', 'CommonDevices.', $tariff_where ) . ";

                  CREATE TEMPORARY TABLE commonDeviceIndications
                      SELECT 
                          DeviceIndications.DeviceId AS DeviceId,
                          SUM( DeviceIndications.PreviousIndications ) AS PreviousIndications,
                          SUM( DeviceIndications.CurrentIndications ) AS CurrentIndications,
                          SUM( DeviceIndications.CurrentIndications ) - SUM( DeviceIndications.PreviousIndications ) AS Indications
                      FROM ( SELECT 
                                 DeviceIndications.DeviceId AS DeviceId,
                                 IFNULL( DeviceIndications.Indications, 0 ) AS PreviousIndications,
                                 0 AS CurrentIndications
                             FROM 
                                 DeviceIndications AS DeviceIndications
                             RIGHT JOIN ( SELECT 
                                              DeviceIndications.DeviceId AS DeviceId,
                                              MAX( DeviceIndications.Date ) AS Date
                                          FROM 
                                              DeviceIndications AS DeviceIndications
                                          WHERE 
                                              ( DeviceIndications.DeviceId IN ( SELECT 
                                                                                    tempCommonDevices.DeviceId AS DeviceID 
                                                                                FROM 
                                                                                    tempCommonDevices AS tempCommonDevices ) ) AND
                                              ( DeviceIndications.Fixed = 1 )
                                          GROUP BY
                                              DeviceIndications.DeviceId ) AS DeviceIndicationsMax ON 
                                 ( DeviceIndicationsMax.DeviceId = DeviceIndications.DeviceId ) AND
                                 ( DeviceIndicationsMax.Date = DeviceIndications.Date )
                             WHERE 
                                 ( DeviceIndications.DeviceId IN ( SELECT 
                                                                       tempCommonDevices.DeviceId AS DeviceID 
                                                                   FROM 
                                                                       tempCommonDevices AS tempCommonDevices ) ) AND
                                 ( DeviceIndications.Fixed = 1 )

                             UNION ALL

                             SELECT 
                                 DeviceIndications.DeviceId AS DeviceId,
                                 0 AS PreviousIndications,
                                 IFNULL( DeviceIndications.Indications, 0 ) AS CurrentIndications
                             FROM 
                                 DeviceIndications AS DeviceIndications
                             RIGHT JOIN ( SELECT 
                                              DeviceIndications.DeviceId AS DeviceId,
                                              MAX( DeviceIndications.Date ) AS Date
                                          FROM 
                                              DeviceIndications AS DeviceIndications
                                          WHERE 
                                              ( DeviceIndications.DeviceId IN ( SELECT 
                                                                                    tempCommonDevices.DeviceId AS DeviceID 
                                                                                FROM 
                                                                                    tempCommonDevices AS tempCommonDevices ) ) AND
                                              ( DeviceIndications.Fixed = 0 )
                                          GROUP BY
                                              DeviceIndications.DeviceId ) AS DeviceIndicationsMax ON 
                                 ( DeviceIndicationsMax.DeviceId = DeviceIndications.DeviceId ) AND
                                 ( DeviceIndicationsMax.Date = DeviceIndications.Date )
                             WHERE 
                                 ( DeviceIndications.DeviceId IN ( SELECT 
                                                                       tempCommonDevices.DeviceId AS DeviceID 
                                                                   FROM 
                                                                       tempCommonDevices AS tempCommonDevices ) ) AND
                                 ( DeviceIndications.Fixed = 0 )
                      ) AS DeviceIndications
                      GROUP BY
                          DeviceIndications.DeviceId;

                  CREATE TEMPORARY TABLE tempCommonDevicesWithIndications
                      SELECT 
                          tempCommonDevices.ObjectId AS ObjectId,
                          tempCommonDevices.ServiceId AS ServiceId,
                          tempCommonDevices.TariffId AS TariffId,
                          tempCommonDevices.DeviceId AS DeviceId,
                          commonDeviceIndications.PreviousIndications AS PreviousIndications,
                          commonDeviceIndications.CurrentIndications AS CurrentIndications,
                          commonDeviceIndications.Indications AS Indications
                      FROM 
                          tempCommonDevices AS tempCommonDevices
                      LEFT JOIN commonDeviceIndications AS commonDeviceIndications ON 
                          ( commonDeviceIndications.DeviceId = tempCommonDevices.DeviceId );

                  CREATE TEMPORARY TABLE tempTariffs
                      SELECT 
                          Tariffs.TariffId AS TariffId,
                          Tariffs.ServiceId AS ServiceId,
                          IFNULL( Tariffs.Price, 0 ) AS Price
                      FROM 
                          Tariffs AS Tariffs
                      RIGHT JOIN ( SELECT 
                                       Tariffs.TariffId AS TariffId,
                                       Tariffs.ServiceId AS ServiceId,
                                       MAX( Tariffs.Date ) AS Date
                                   FROM 
                                       Tariffs AS Tariffs
                                   WHERE 
                                       ( Tariffs.ServiceId IN ( SELECT 
                                                                    AccountServices.ServiceId AS ServiceId
                                                                FROM 
                                                                    AccountServices AS AccountServices
                                                                WHERE 
                                                                    ( AccountServices.AccountId = @account_id ) AND
                                                                    ( AccountServices.`Using` = 1 )
																	" . str_replace( 'Alias.', 'AccountServices.', $service_where ) . " ) ) AND
                                       ( Tariffs.CompanyId IN ( SELECT 
                                                                     PersonalAccounts.CompanyId AS CompanyId
                                                                FROM 
                                                                     PersonalAccounts AS PersonalAccounts
                                                                WHERE 
                                                                     ( PersonalAccounts.AccountId = @account_id ) AND
                                                                     ( PersonalAccounts.`Using` = 1 ) ) )
									   " . str_replace( 'Alias.', 'Tariffs.', $tariff_where ) . "
                                   GROUP BY
                                       Tariffs.TariffId,
                                       Tariffs.ServiceId ) AS TariffsMax ON 
                          ( TariffsMax.TariffId = Tariffs.TariffId ) AND
                          ( TariffsMax.ServiceId = Tariffs.ServiceId ) AND
                          ( TariffsMax.Date = Tariffs.Date )
                      WHERE 
                          ( Tariffs.ServiceId IN ( SELECT 
                                                       AccountServices.ServiceId AS ServiceId
                                                   FROM 
                                                       AccountServices AS AccountServices
                                                   WHERE 
                                                      ( AccountServices.AccountId = @account_id ) AND
                                                      ( AccountServices.`Using` = 1 )
													  " . str_replace( 'Alias.', 'AccountServices.', $service_where ) . " ) ) AND
                          ( Tariffs.CompanyId IN ( SELECT 
                                                       PersonalAccounts.CompanyId AS CompanyId
                                                   FROM 
                                                       PersonalAccounts AS PersonalAccounts
                                                   WHERE 
                                                       ( PersonalAccounts.AccountId = @account_id ) AND
                                                       ( PersonalAccounts.`Using` = 1 ) ) )
						  " . str_replace( 'Alias.', 'Tariffs.', $tariff_where ) . ";

                  CREATE TEMPORARY TABLE tempDebt
                      SELECT 
                          Debt.AccountId AS AccountId,
                          MAX( IFNULL( Debt.Summa, 0 ) ) - SUM( IFNULL( Payment.Summa, 0 ) ) AS Summa
                      FROM 
                          Debt AS Debt
                      RIGHT JOIN ( SELECT 
                                       Debt.AccountId AS AccountId,
                                       MAX( Debt.Period ) AS Period
                                   FROM 
                                       Debt AS Debt
                                   WHERE 
                                       ( Debt.AccountId = @account_id )
                                   GROUP BY
                                       Debt.AccountId ) AS DebtMax ON 
                          ( DebtMax.AccountId = Debt.AccountId ) AND
                          ( DebtMax.Period = Debt.Period )
                      LEFT JOIN Payment AS Payment ON
                          ( Payment.AccountId = Debt.AccountId ) AND
                          ( Payment.Date > Debt.Period )
                      WHERE 
                          ( Debt.AccountId = @account_id )
                      GROUP BY
                          Debt.AccountId;

                  SELECT 
                      AccountServices.AccountId AS AccountId,
                      IFNULL( PersonalAccounts.Name, '' ) AS AccountName,
                      PersonalAccounts.AbonentId AS AbonentId,
                      IFNULL( Abonents.Name, '' ) AS AbonentName,
                      PersonalAccounts.ObjectId AS ObjectId,
                      IFNULL( Objects.Name, '' ) AS ObjectName,
                      IFNULL( Objects.KadastrNo, '' ) AS ObjectKadastrNo,
                      IFNULL( Objects.Address, '' ) AS ObjectAddress,
                      PersonalAccounts.CompanyId AS CompanyId,
                      IFNULL( ManagementCompany.Name, '' ) AS CompanyName,
                      PersonalAccounts.StartDate AS StartDate,
                      PersonalAccounts.EndDate AS EndDate,
                      PersonalAccounts.`Using` AS IsUsing,
					  IFNULL( AccountUsers.Access, 0 ) AS Access,
                      ROUND( IFNULL( tempDebt.Summa, 0 ), 2 ) AS Debt,
	                  AccountServices.ServiceId AS ServiceId,
                      IFNULL( Services.Name, '' ) AS ServiceName,
                      Services.UnitId AS UnitId,
                      IFNULL( Units.Name, '' ) AS UnitName,
                      AccountServices.TariffId AS TariffId,
                      IFNULL( TariffTypes.Name, '' ) AS TariffName,
                      IFNULL( TariffTypes.IsNormative, 1 ) AS TariffIsNormative,
                      ROUND( IFNULL( AccountServices.Portion, 1 ), 5 ) AS Portion,
                      ROUND( IFNULL( AccountServices.Сoefficient, 0 ), 5 ) AS Сoefficient,
                      AccountServices.CalculationType AS CalculationType,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 1 ) THEN ROUND( IFNULL( tempAccountNormatives.Count, 0 ), 5 )
                          ELSE NULL
                      END AS NormativeCount,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 2 ) THEN tempAccountDevicesWithIndications.DeviceId
                          ELSE NULL 
                      END AS DeviceId,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 2 ) THEN IFNULL( Devices.Name, '' ) 
                          ELSE NULL 
                      END AS DeviceName,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 2 ) THEN Devices.NextDateCheck
                          ELSE NULL
                      END AS DeviceNextDateCheck,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 2 ) THEN ROUND( IFNULL( tempAccountDevicesWithIndications.PreviousIndications, 0 ), 5 )
                          ELSE NULL
                      END AS PreviousIndications,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 2 ) THEN ROUND( IFNULL( tempAccountDevicesWithIndications.CurrentIndications, 0 ), 5 )
                          ELSE NULL
                      END AS CurrentIndications,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 2 ) THEN ROUND( IFNULL( tempAccountDevicesWithIndications.Indications, 0 ), 5 )
                          ELSE NULL
                      END AS Indications,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 3 ) THEN ROUND( IFNULL( tempAccountNormatives.Count, 0 ), 2 )
                          ELSE NULL
                      END AS FixedSumma,
                      CASE 
                          WHEN ( ( AccountServices.CalculationType = 4 ) OR ( AccountServices.CalculationType = 5 ) ) THEN ROUND( IFNULL( commonDeviceIndications.Indications, 0 ), 5 )
                          ELSE NULL
                      END AS CommonIndications,
                      CASE 
                          WHEN ( ( AccountServices.CalculationType = 4 ) OR ( AccountServices.CalculationType = 5 ) ) THEN ROUND( IFNULL( totalDeviceIndications.Indications, 0 ), 5 )
                          ELSE NULL
                      END AS TotalIndications,
                      CASE 
                          WHEN ( ( AccountServices.CalculationType = 4 ) OR ( AccountServices.CalculationType = 5 ) ) THEN ROUND( IFNULL( commonDeviceIndications.Indications, 0 ) - IFNULL( totalDeviceIndications.Indications, 0 ), 5 )
                          ELSE NULL
                      END AS SharedIndications,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 4 ) THEN ROUND( IFNULL( tempObjects.Square, 0 ), 5 )
                          ELSE NULL
                      END AS ObjectSquare,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 4 ) THEN ROUND( IFNULL( totalObjects.Square, 0 ), 5 )
                          ELSE NULL
                      END AS TotalSquare,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 4 ) THEN
                              CASE 
                                    WHEN ( IFNULL( totalObjects.Square, 0 ) = 0 ) THEN 0
                                    ELSE IFNULL( tempObjects.Square, 0 ) / IFNULL( totalObjects.Square, 0 )
                              END
                          ELSE NULL
                      END AS PortionSquare,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 5 ) THEN ROUND( IFNULL( individualDeviceIndications.Indications, 0 ), 5 )
                          ELSE NULL
                      END AS IndividualIndications,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 5 ) THEN
                              CASE 
                                    WHEN ( IFNULL( totalDeviceIndications.Indications, 0 ) = 0 ) THEN 0
                                    ELSE IFNULL( individualDeviceIndications.Indications, 0 ) / IFNULL( totalDeviceIndications.Indications, 0 )
                              END
                          ELSE NULL
                      END AS PortionIndications,
                      CASE 
                          WHEN ( AccountServices.CalculationType = 3 ) THEN NULL
                          ELSE ROUND( IFNULL( tempTariffs.Price, 0 ), 2 )
                      END AS Price,    
                      ROUND( CASE 
                                 WHEN ( AccountServices.CalculationType = 1 ) 
                                       THEN IFNULL( tempAccountNormatives.Count, 0 ) * IFNULL( tempTariffs.Price, 0 )
                                 WHEN ( AccountServices.CalculationType = 2 ) 
                                       THEN IFNULL( tempAccountDevicesWithIndications.Indications, 0 ) * IFNULL( tempTariffs.Price, 0 )
                                 WHEN ( AccountServices.CalculationType = 3 ) 
                                       THEN IFNULL( tempAccountNormatives.Count, 0 )
                                 WHEN ( AccountServices.CalculationType = 4 ) 
                                       THEN CASE 
                                                WHEN ( IFNULL( totalObjects.Square, 0 ) = 0 ) THEN 0
                                                ELSE IFNULL( tempObjects.Square, 0 ) / IFNULL( totalObjects.Square, 0 )
                                            END * ( IFNULL( commonDeviceIndications.Indications, 0 ) - IFNULL( totalDeviceIndications.Indications, 0 ) ) * IFNULL( tempTariffs.Price, 0 )
                                 WHEN ( AccountServices.CalculationType = 5 ) 
                                       THEN CASE 
                                                WHEN ( IFNULL( totalDeviceIndications.Indications, 0 ) = 0 ) THEN 0
                                                ELSE IFNULL( individualDeviceIndications.Indications, 0 ) / IFNULL( totalDeviceIndications.Indications, 0 )
                                            END * ( IFNULL( commonDeviceIndications.Indications, 0 ) - IFNULL( totalDeviceIndications.Indications, 0 ) ) * IFNULL( tempTariffs.Price, 0 )
                             END * IFNULL( AccountServices.Portion, 1 ) * ( 1 + IFNULL( AccountServices.Сoefficient, 0 ) ), 2 ) AS Summa    
                  FROM 
                      AccountServices AS AccountServices
                  LEFT JOIN PersonalAccounts AS PersonalAccounts ON 
                      ( PersonalAccounts.AccountId = AccountServices.AccountId )
                  LEFT JOIN Abonents AS Abonents ON 
                      ( Abonents.AbonentId = PersonalAccounts.AbonentId )
                  LEFT JOIN Objects AS Objects ON 
                      ( Objects.ObjectId = PersonalAccounts.ObjectId )
                  LEFT JOIN ManagementCompany AS ManagementCompany ON 
                      ( ManagementCompany.CompanyId = PersonalAccounts.CompanyId )
                  LEFT JOIN tempDebt AS tempDebt ON 
                      ( tempDebt.AccountId = AccountServices.AccountId )
                  LEFT JOIN AccountUsers AS AccountUsers ON 
                      ( AccountUsers.UserId = @user_id ) AND
                      ( AccountUsers.AccountId = AccountServices.AccountId )
                  LEFT JOIN Services AS Services ON 
                      ( Services.ServiceId = AccountServices.ServiceId )
                  LEFT JOIN Units AS Units ON 
                      ( Units.UnitId = Services.UnitId )
                  LEFT JOIN TariffTypes AS TariffTypes ON 
                      ( TariffTypes.TariffId = AccountServices.TariffId )
                  LEFT JOIN tempAccountNormatives AS tempAccountNormatives ON 
                      ( tempAccountNormatives.AccountId = AccountServices.AccountId ) AND
                      ( tempAccountNormatives.ServiceId = AccountServices.ServiceId ) AND
                      ( tempAccountNormatives.TariffId = AccountServices.TariffId ) AND
                      ( IFNULL( TariffTypes.IsNormative, 1 ) = 1 ) AND
                      ( ( AccountServices.CalculationType = 1 ) OR ( AccountServices.CalculationType = 3 ) )
                  LEFT JOIN tempAccountDevicesWithIndications AS tempAccountDevicesWithIndications ON 
                      ( tempAccountDevicesWithIndications.AccountId = AccountServices.AccountId ) AND
                      ( tempAccountDevicesWithIndications.ServiceId = AccountServices.ServiceId ) AND
                      ( tempAccountDevicesWithIndications.TariffId = AccountServices.TariffId ) AND
                      ( IFNULL( TariffTypes.IsNormative, 1 ) = 0 ) AND
                      ( AccountServices.CalculationType = 2 )
                  LEFT JOIN Devices AS Devices ON 
                      ( Devices.DeviceId = tempAccountDevicesWithIndications.DeviceId )
                  LEFT JOIN ( SELECT 
                                  tempCommonDevicesWithIndications.ObjectId AS ObjectId,
                                  tempCommonDevicesWithIndications.ServiceId AS ServiceId,
                                  tempCommonDevicesWithIndications.TariffId AS TariffId,
                                  SUM( tempCommonDevicesWithIndications.Indications ) AS Indications
                              FROM 
                                  tempCommonDevicesWithIndications AS tempCommonDevicesWithIndications
                              GROUP BY
                                  tempCommonDevicesWithIndications.ObjectId,
                                  tempCommonDevicesWithIndications.ServiceId,
                                  tempCommonDevicesWithIndications.TariffId ) AS commonDeviceIndications ON 
                      ( commonDeviceIndications.ObjectId = @parent_id ) AND
                      ( commonDeviceIndications.ServiceId = AccountServices.ServiceId ) AND
                      ( commonDeviceIndications.TariffId = AccountServices.TariffId ) AND
                      ( ( AccountServices.CalculationType = 4 ) OR ( AccountServices.CalculationType = 5 ) )
                  LEFT JOIN ( SELECT 
                                  tempAccountDevicesWithIndications.ServiceId AS ServiceId,
                                  tempAccountDevicesWithIndications.TariffId AS TariffId,
                                  SUM( tempAccountDevicesWithIndications.Indications ) AS Indications
                              FROM 
                                  tempAccountDevicesWithIndications AS tempAccountDevicesWithIndications
                              GROUP BY
                                  tempAccountDevicesWithIndications.ServiceId,
                                  tempAccountDevicesWithIndications.TariffId ) AS totalDeviceIndications ON 
                      ( totalDeviceIndications.ServiceId = AccountServices.ServiceId ) AND
                      ( totalDeviceIndications.TariffId = AccountServices.TariffId ) AND
                      ( ( AccountServices.CalculationType = 4 ) OR ( AccountServices.CalculationType = 5 ) )
                  LEFT JOIN tempObjects AS tempObjects ON    
                      ( tempObjects.AccountID = AccountServices.AccountId ) AND
                      ( AccountServices.CalculationType = 4 )
                  LEFT JOIN ( SELECT
                                    SUM( tempObjects.Square ) AS Square
                              FROM tempObjects AS tempObjects ) AS totalObjects ON
                      ( AccountServices.CalculationType = 4 )
                  LEFT JOIN ( SELECT 
                                  tempAccountDevicesWithIndications.AccountId AS AccountId,
                                  tempAccountDevicesWithIndications.ServiceId AS ServiceId,
                                  tempAccountDevicesWithIndications.TariffId AS TariffId,
                                  SUM( tempAccountDevicesWithIndications.Indications ) AS Indications
                              FROM 
                                  tempAccountDevicesWithIndications AS tempAccountDevicesWithIndications
                              GROUP BY
                                  tempAccountDevicesWithIndications.AccountId,
                                  tempAccountDevicesWithIndications.ServiceId,
                                  tempAccountDevicesWithIndications.TariffId ) AS individualDeviceIndications ON 
                      ( individualDeviceIndications.AccountId = AccountServices.AccountId ) AND
                      ( individualDeviceIndications.ServiceId = AccountServices.ServiceId ) AND
                      ( individualDeviceIndications.TariffId = AccountServices.TariffId ) AND
                      ( AccountServices.CalculationType = 5 )
                  LEFT JOIN tempTariffs AS tempTariffs ON 
                      ( tempTariffs.TariffId = AccountServices.TariffId ) AND
                      ( tempTariffs.ServiceId = AccountServices.ServiceId )
                  WHERE 
                      ( AccountServices.AccountId = @account_id ) AND
                      ( AccountServices.`Using` = 1 ) AND
					  ( IFNULL( AccountUsers.Active, 0 ) = 1 ) 
					  " . str_replace( 'Alias.', 'AccountServices.', $service_where ) . " 
					  " . str_replace( 'Alias.', 'AccountServices.', $tariff_where ) . "
					  " . str_replace( 'Alias.', 'AccountServices.', $calculation_type_where ) . " 
                  ORDER BY
                      IFNULL( Services.Name, '' ),
                      AccountServices.CalculationType,
                      IFNULL( Devices.Name, '' )";
			
		if ( mysqli_multi_query( $mysqli, $query ) ) { 
			do {
				if ( $result = mysqli_store_result( $mysqli ) ) {
					if ( !mysqli_more_results( $mysqli ) ) {
						break;
					}
					
					mysqli_free_result( $result );
				}
			} while ( mysqli_next_result( $mysqli ) );

			$data             = null;
			$service_id       = null;
			$tariff_id        = null;
			$calculation_type = null;
			$summa            = 0;

			while ( $row = mysqli_fetch_array( $result ) ) { 
				if ( is_null( $data ) ) {
					$data = [ 'account'  => [ 'id'         => $row[ 'AccountId' ],
											  'name'       => $row[ 'AccountName' ],
											  'start_date' => $row[ 'StartDate' ],
											  'end_date'   => $row[ 'EndDate' ],
											  'is_using'   => ( $row[ 'IsUsing' ] == 1 ) ],
							  'abonent'  => [ 'id'         => $row[ 'AbonentId' ],
											  'name'       => $row[ 'AbonentName' ] ],
							  'object'   => [ 'id'         => $row[ 'ObjectId' ],
											  'name'       => $row[ 'ObjectName' ],
											  'kadastr_no' => $row[ 'ObjectKadastrNo' ],
											  'address'    => $row[ 'ObjectAddress' ] ],
							  'company'  => [ 'id'         => $row[ 'CompanyId' ],
											  'name'       => $row[ 'CompanyName' ] ],
							  'access'   => ( $row[ 'Access' ] == 1 ),
							  'debt'     => round( $row[ 'Debt' ], 2, PHP_ROUND_HALF_UP ),
							  'services' => array() ];
				}

				$summa = $summa + $row[ 'Summa' ];
				
				if ( ( $service_id       !== $row[ 'ServiceId' ]       ) ||
					 ( $tariff_id        !== $row[ 'TariffId' ]        ) ||
					 ( $calculation_type !== $row[ 'CalculationType' ] ) ) {
					$service_id       = $row[ 'ServiceId' ];
					$tariff_id        = $row[ 'TariffId' ];
					$calculation_type = $row[ 'CalculationType' ];
					
					$services = [ 'service'     => [ 'id'           => $row[ 'ServiceId' ],
													 'name'         => $row[ 'ServiceName' ] ],
								  'unit'        => [ 'id'           => $row[ 'UnitId' ],
													 'name'         => $row[ 'UnitName' ] ],
								  'tariff'      => [ 'id'           => $row[ 'TariffId' ],
													 'name'         => $row[ 'TariffName' ],
													 'is_normative' => $row[ 'TariffIsNormative' ],
													 'price'        => round( $row[ 'Price' ], 2, PHP_ROUND_HALF_UP ) ],
								  'portion'     => round( $row[ 'Portion' ], 5, PHP_ROUND_HALF_UP ),
								  'coefficient' => round( $row[ 'Сoefficient' ], 5, PHP_ROUND_HALF_UP ),
								  'summa'       => round( $row[ 'Summa' ], 2, PHP_ROUND_HALF_UP ) ];
								  
					if ( $row[ 'CalculationType' ] == 1 ) {
						$services += [ 'normative'   => round( $row[ 'NormativeCount' ], 5, PHP_ROUND_HALF_UP ),
									   'volume'      => round( $row[ 'NormativeCount' ], 5, PHP_ROUND_HALF_UP ),
									   'calculation' => [ 'type'        => $row[ 'CalculationType' ],
														  'name'        => 'по нормативам потребления',
														  'coefficient' => round( $row[ 'Portion' ] * ( 1 + $row[ 'Сoefficient' ] ), 5, PHP_ROUND_HALF_UP ) ] ];
					}
					else if ( $row[ 'CalculationType' ] == 2 ) {
						$services += [ 'indications' => round( $row[ 'Indications' ], 5, PHP_ROUND_HALF_UP ),
									   'volume'      => round( $row[ 'Indications' ], 5, PHP_ROUND_HALF_UP ),
									   'calculation' => [ 'type'        => $row[ 'CalculationType' ],
														  'name'        => 'по показаниям приборов учета',
														  'coefficient' => round( $row[ 'Portion' ] * ( 1 + $row[ 'Сoefficient' ] ), 5, PHP_ROUND_HALF_UP ) ],
									   'devices'     => [ [ 'id'                   => $row[ 'DeviceId' ],
															'name'                 => $row[ 'DeviceName' ],
															'next_date_check'      => $row[ 'DeviceNextDateCheck' ],
															'previous_indications' => round( $row[ 'PreviousIndications' ], 5, PHP_ROUND_HALF_UP ),
															'current_indications'  => round( $row[ 'CurrentIndications' ], 5, PHP_ROUND_HALF_UP ),
															'indications'          => round( $row[ 'Indications' ], 5, PHP_ROUND_HALF_UP ) ] ] ];
					}
					else if ( $row[ 'CalculationType' ] == 3 ) {
						$services += [ 'normative'   => round( $row[ 'FixedSumma' ], 2, PHP_ROUND_HALF_UP ),
									   'calculation' => [ 'type'        => $row[ 'CalculationType' ],
														  'name'        => 'фиксированной суммой',
														  'coefficient' => round( $row[ 'Portion' ] * ( 1 + $row[ 'Сoefficient' ] ), 5, PHP_ROUND_HALF_UP ) ] ];
					}
					else if ( $row[ 'CalculationType' ] == 4 ) {
						$services += [ 'common_indications' => round( $row[ 'CommonIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'total_indications'  => round( $row[ 'TotalIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'shared_indications' => round( $row[ 'SharedIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'object_square'      => round( $row[ 'ObjectSquare' ], 5, PHP_ROUND_HALF_UP ),
									   'total_square'       => round( $row[ 'TotalSquare' ], 5, PHP_ROUND_HALF_UP ),
									   'portion_square'     => round( $row[ 'PortionSquare' ], 5, PHP_ROUND_HALF_UP ),
									   'volume'             => round( $row[ 'SharedIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'calculation'        => [ 'type'        => $row[ 'CalculationType' ],
																 'name'        => 'пропорционально площади объектов недвижимости',
																 'coefficient' => round( $row[ 'PortionSquare' ] * $row[ 'Portion' ] * ( 1 + $row[ 'Сoefficient' ] ), 5, PHP_ROUND_HALF_UP ) ] ];
					}
					else if ( $row[ 'CalculationType' ] == 5 ) {
						$services += [ 'common_indications'     => round( $row[ 'CommonIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'total_indications'      => round( $row[ 'TotalIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'shared_indications'     => round( $row[ 'SharedIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'individual_indications' => round( $row[ 'IndividualIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'portion_indications'    => round( $row[ 'PortionIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'volume'                 => round( $row[ 'SharedIndications' ], 5, PHP_ROUND_HALF_UP ),
									   'calculation'            => [ 'type'        => $row[ 'CalculationType' ],
																	 'name'        => 'пропорционально показаниям приборов учета',
																	 'coefficient' => round( $row[ 'PortionIndications' ] * $row[ 'Portion' ] * ( 1 + $row[ 'Сoefficient' ] ), 5, PHP_ROUND_HALF_UP ) ] ];
					}
												   
					array_push( $data[ 'services' ], $services );
				}
				else if ( $row[ 'CalculationType' ] == 2 ) {
					$devices = [ 'id'                   => $row[ 'DeviceId' ],
								 'name'                 => $row[ 'DeviceName' ],
								 'next_date_check'      => $row[ 'DeviceNextDateCheck' ],
								 'previous_indications' => round( $row[ 'PreviousIndications' ], 5, PHP_ROUND_HALF_UP ),
								 'current_indications'  => round( $row[ 'CurrentIndications' ], 5, PHP_ROUND_HALF_UP ),
								 'indications'          => round( $row[ 'Indications' ], 5, PHP_ROUND_HALF_UP ) ];
					
					$services                  = array_pop( $data[ 'services' ] );
					$services[ 'indications' ] = $services[ 'indications' ] + round( $row[ 'Indications' ], 5, PHP_ROUND_HALF_UP );
					$services[ 'volume' ]      = $services[ 'volume' ] + round( $row[ 'Indications' ], 5, PHP_ROUND_HALF_UP );
					$services[ 'summa' ]       = $services[ 'summa' ] + round( $row[ 'Summa' ], 5, PHP_ROUND_HALF_UP );
					
					array_push( $services[ 'devices' ], $devices );
					array_push( $data[ 'services' ], $services );
				}
			}

			if ( !is_null( $data ) ) {
				$data += [ 'summa' => round( $summa, 2, PHP_ROUND_HALF_UP ) ];
			}

			mysqli_free_result( $result );
			return $data;
		}
		
		return false;
	}


	function commonUpdateDeviceIndications( $device_id, $indications ) {
		global $mysqli;

		$query = "INSERT INTO DeviceIndications ( DeviceId, Date, Indications, Fixed ) 
				  VALUES ( " . $device_id . ", NOW(), " . $indications . ", 0 )";

		return mysqli_query( $mysqli, $query );
	}
?>