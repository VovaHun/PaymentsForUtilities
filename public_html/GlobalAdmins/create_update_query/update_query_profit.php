<?php
    mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );

	include_once $_SERVER[ 'DOCUMENT_ROOT' ] . "/api/_connection.php";

	$CompanyId = $_POST[ "company" ];
	$Period    = $_POST[ "date" ];
	$Period    = str_replace ( 'T', ' ', $Period );

	// По нормативам потребления
	$query = "SET @company_id       := " . $CompanyId . ";
				SET @calculation_type := 1;
				SET @start_date       := '" . $Period . "';
				SET @start_date       := LAST_DAY( @start_date ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND;
				SET @end_date         := DATE_ADD( @start_date, INTERVAL 1 MONTH );
				SET @id               := 0;


				CREATE TEMPORARY TABLE tempPersonalAccounts
					SELECT
						PersonalAccounts.AccountId AS AccountId
					FROM 
						PersonalAccounts AS PersonalAccounts
					WHERE
						( PersonalAccounts.CompanyId = @company_id ) AND
						( IFNULL( PersonalAccounts.Using, 0 ) = 1 );


				CREATE TEMPORARY TABLE prevAccountServices
					SELECT 
						@start_date AS Date,
						AccountServicesMax.AccountId AS AccountId,
						AccountServicesMax.ServiceId AS ServiceId,
						AccountServicesMax.TariffId AS TariffId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient
					FROM ( 
						SELECT 
							MAX( AccountServices.Date ) AS DateMax,
							AccountServices.AccountId AS AccountId,
							AccountServices.ServiceId AS ServiceId,
							AccountServices.TariffId AS TariffId
						FROM 
							AccountServices AS AccountServices
						WHERE 
							( AccountServices.Date < @start_date ) AND 
							( AccountServices.AccountId IN ( SELECT 
																 tempPersonalAccounts.AccountId AS AccountID 
															 FROM 
																 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
							( AccountServices.CalculationType = @calculation_type )
						GROUP BY
							AccountServices.AccountId,
							AccountServices.ServiceId,
							AccountServices.TariffId,
							AccountServices.CalculationType ) AS AccountServicesMax
					LEFT JOIN AccountServices AS AccountServices ON 
						( AccountServices.Date = AccountServicesMax.DateMax ) AND
						( AccountServices.AccountId = AccountServicesMax.AccountId ) AND
						( AccountServices.ServiceId = AccountServicesMax.ServiceId ) AND
						( AccountServices.TariffId = AccountServicesMax.TariffId ) AND
						( AccountServices.CalculationType = @calculation_type )

					UNION ALL

					SELECT 
						AccountServices.Date AS Date,
						AccountServices.AccountId AS AccountId,
						AccountServices.ServiceId AS ServiceId,
						AccountServices.TariffId AS TariffId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient
					FROM 
						AccountServices AS AccountServices
					WHERE 
						( AccountServices.Date >= @start_date ) AND 
						( AccountServices.Date < @end_date ) AND 
						( AccountServices.AccountId IN ( SELECT 
															 tempPersonalAccounts.AccountId AS AccountID 
														 FROM 
															 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
						( AccountServices.CalculationType = @calculation_type );


				CREATE TEMPORARY TABLE tempAccountServices
					SELECT 
						@id := @id + 1 AS Id,
						prevAccountServices.Date AS StartDate,
						IFNULL( ( SELECT 
								nextAccountServices.Date
							FROM 
								prevAccountServices AS nextAccountServices
							WHERE
								( prevAccountServices.Date < nextAccountServices.Date ) AND
								( prevAccountServices.AccountId = nextAccountServices.AccountId ) AND
								( prevAccountServices.ServiceId = nextAccountServices.ServiceId ) AND
								( prevAccountServices.TariffId = nextAccountServices.TariffId )
							ORDER BY
								prevAccountServices.AccountId,
								prevAccountServices.ServiceId,
								prevAccountServices.TariffId,
								prevAccountServices.Date
							LIMIT 1 ), @end_date ) AS EndDate,
						prevAccountServices.AccountId AS AccountId,
						prevAccountServices.ServiceId AS ServiceId,
						prevAccountServices.TariffId AS TariffId,
						prevAccountServices.Portion AS Portion,
						prevAccountServices.Сoefficient AS Сoefficient
					FROM 
						prevAccountServices AS prevAccountServices
					LEFT JOIN TariffTypes AS TariffTypes ON 
						( TariffTypes.TariffId = prevAccountServices.TariffId )
					WHERE
						( IFNULL( TariffTypes.IsNormative, 1 ) = 1 )
					ORDER BY
						prevAccountServices.AccountId,
						prevAccountServices.ServiceId,
						prevAccountServices.TariffId,
						prevAccountServices.Date;


				CREATE TEMPORARY TABLE prevAccountNormatives
					SELECT 
						tempAccountServices.Id AS Id,
						IFNULL( AccountNormatives.Date, tempAccountServices.StartDate ) AS StartDate,
						tempAccountServices.EndDate AS EndDate,
						tempAccountServices.AccountId AS AccountId,
						tempAccountServices.ServiceId AS ServiceId,
						tempAccountServices.TariffId AS TariffId,
						tempAccountServices.Portion AS Portion,
						tempAccountServices.Сoefficient AS Сoefficient,
						IFNULL( AccountNormatives.Count, 0 ) AS Count
					FROM 
						tempAccountServices AS tempAccountServices
					LEFT JOIN 
						( SELECT 
							AccountNormativesMax.Id AS Id,
							AccountNormativesMax.StartDate AS Date,
							AccountNormativesMax.AccountId AS AccountId,
							AccountNormativesMax.ServiceId AS ServiceId,
							AccountNormativesMax.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( AccountNormatives.Using, 0 ) = 1 ) THEN IFNULL( AccountNormatives.Count, 0 )
								ELSE 0
							END AS Count
						FROM 
							( SELECT 
								tempAccountServices.Id AS Id,
								tempAccountServices.StartDate AS StartDate,
								AccountNormatives.AccountId AS AccountId,
								AccountNormatives.ServiceId AS ServiceId,
								AccountNormatives.TariffId AS TariffId,
								MAX( AccountNormatives.Date ) AS DateMax
							FROM 
								tempAccountServices AS tempAccountServices
							LEFT JOIN AccountNormatives AS AccountNormatives ON 
								( AccountNormatives.Date <= tempAccountServices.StartDate ) AND
								( AccountNormatives.AccountId = tempAccountServices.AccountId ) AND
								( AccountNormatives.ServiceId = tempAccountServices.ServiceId ) AND
								( AccountNormatives.TariffId = tempAccountServices.TariffId )
							WHERE 
								( AccountNormatives.AccountId IN ( SELECT 
																	   tempAccountServices.AccountId AS AccountID 
																   FROM 
																	   tempAccountServices AS tempAccountServices ) )
							GROUP BY
								tempAccountServices.Id,
								tempAccountServices.StartDate,
								AccountNormatives.AccountId,
								AccountNormatives.ServiceId,
								AccountNormatives.TariffId ) AS AccountNormativesMax
						LEFT JOIN AccountNormatives AS AccountNormatives ON 
							( AccountNormatives.AccountId = AccountNormativesMax.AccountId ) AND
							( AccountNormatives.ServiceId = AccountNormativesMax.ServiceId ) AND
							( AccountNormatives.TariffId = AccountNormativesMax.TariffId ) AND
							( AccountNormatives.Date = AccountNormativesMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountServices.Id AS Id,
							AccountNormatives.Date AS Date,
							AccountNormatives.AccountId AS AccountId,
							AccountNormatives.ServiceId AS ServiceId,
							AccountNormatives.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( AccountNormatives.Using, 0 ) = 1 ) THEN IFNULL( AccountNormatives.Count, 0 )
								ELSE 0
							END AS Count
						FROM 
							tempAccountServices AS tempAccountServices
						LEFT JOIN AccountNormatives AS AccountNormatives ON 
							( AccountNormatives.Date > tempAccountServices.StartDate ) AND
							( AccountNormatives.Date <= tempAccountServices.EndDate ) AND
							( AccountNormatives.AccountId = tempAccountServices.AccountId ) AND
							( AccountNormatives.ServiceId = tempAccountServices.ServiceId ) AND
							( AccountNormatives.TariffId = tempAccountServices.TariffId )
						WHERE 
							( AccountNormatives.AccountId IN ( SELECT 
																   tempAccountServices.AccountId AS AccountID 
															   FROM 
																   tempAccountServices AS tempAccountServices ) ) ) AS AccountNormatives ON 
						( AccountNormatives.Id = tempAccountServices.Id ) AND
						( AccountNormatives.Date >= tempAccountServices.StartDate ) AND
						( AccountNormatives.Date < tempAccountServices.EndDate );


				CREATE TEMPORARY TABLE tempAccountNormatives
					SELECT 
						prevAccountNormatives.Id AS Id,
						prevAccountNormatives.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextAccountNormatives.StartDate
							FROM 
								prevAccountNormatives AS nextAccountNormatives
							WHERE
								( prevAccountNormatives.StartDate < nextAccountNormatives.StartDate ) AND
								( prevAccountNormatives.Id = nextAccountNormatives.Id )
							ORDER BY
								prevAccountNormatives.Id,
								prevAccountNormatives.StartDate
							LIMIT 1 ), prevAccountNormatives.EndDate ) AS EndDate,
						prevAccountNormatives.AccountId AS AccountId,
						prevAccountNormatives.ServiceId AS ServiceId,
						prevAccountNormatives.TariffId AS TariffId,
						prevAccountNormatives.Portion AS Portion,
						prevAccountNormatives.Сoefficient AS Сoefficient,
						prevAccountNormatives.Count AS Count
					FROM 
						prevAccountNormatives AS prevAccountNormatives
					ORDER BY
						prevAccountNormatives.Id,
						prevAccountNormatives.StartDate;


				CREATE TEMPORARY TABLE prevTariffsNormatives
					SELECT 
						tempAccountNormatives.Id AS Id,
						IFNULL( Tariffs.Date, tempAccountNormatives.StartDate ) AS StartDate,
						tempAccountNormatives.EndDate AS EndDate,
						tempAccountNormatives.AccountId AS AccountId,
						tempAccountNormatives.ServiceId AS ServiceId,
						tempAccountNormatives.TariffId AS TariffId,
						tempAccountNormatives.Portion AS Portion,
						tempAccountNormatives.Сoefficient AS Сoefficient,
						tempAccountNormatives.Count AS Count,
						IFNULL( Tariffs.Price, 0 ) AS Price
					FROM 
						tempAccountNormatives AS tempAccountNormatives
					LEFT JOIN 
						( SELECT 
							TariffsMax.Id AS Id,
							TariffsMax.StartDate AS Date,
							TariffsMax.ServiceId AS ServiceId,
							TariffsMax.TariffId AS TariffId,
							IFNULL( Tariffs.Price, 0 ) AS Price
						FROM 
							( SELECT 
								tempAccountNormatives.Id AS Id,
								tempAccountNormatives.StartDate AS StartDate,
								Tariffs.ServiceId AS ServiceId,
								Tariffs.TariffId AS TariffId,
								MAX( Tariffs.Date ) AS DateMax
							FROM 
								tempAccountNormatives AS tempAccountNormatives
							LEFT JOIN Tariffs AS Tariffs ON 
								( Tariffs.Date <= tempAccountNormatives.StartDate ) AND
								( Tariffs.CompanyId = @company_id ) AND
								( Tariffs.ServiceId = tempAccountNormatives.ServiceId ) AND
								( Tariffs.TariffId = tempAccountNormatives.TariffId )
							WHERE 
								( Tariffs.ServiceId IN ( SELECT 
															 tempAccountNormatives.ServiceId AS ServiceId 
														 FROM 
															 tempAccountNormatives AS tempAccountNormatives ) )
							GROUP BY
								tempAccountNormatives.Id,
								tempAccountNormatives.StartDate,
								Tariffs.ServiceId,
								Tariffs.TariffId ) AS TariffsMax
						LEFT JOIN Tariffs AS Tariffs ON 
							( Tariffs.CompanyId = @company_id ) AND
							( Tariffs.ServiceId = TariffsMax.ServiceId ) AND
							( Tariffs.TariffId = TariffsMax.TariffId ) AND
							( Tariffs.Date = TariffsMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountNormatives.Id AS Id,
							Tariffs.Date AS Date,
							Tariffs.ServiceId AS ServiceId,
							Tariffs.TariffId AS TariffId,
							IFNULL( Tariffs.Price, 0 ) AS Price
						FROM 
							tempAccountNormatives AS tempAccountNormatives
						LEFT JOIN Tariffs AS Tariffs ON 
							( Tariffs.Date > tempAccountNormatives.StartDate ) AND
							( Tariffs.Date <= tempAccountNormatives.EndDate ) AND
							( Tariffs.CompanyId = @company_id ) AND
							( Tariffs.ServiceId = tempAccountNormatives.ServiceId ) AND
							( Tariffs.TariffId = tempAccountNormatives.TariffId )
						WHERE 
							( Tariffs.ServiceId IN ( SELECT 
														 tempAccountNormatives.ServiceId AS ServiceId 
													 FROM 
														 tempAccountNormatives AS tempAccountNormatives ) ) ) AS Tariffs ON 
						( Tariffs.Id = tempAccountNormatives.Id ) AND
						( Tariffs.Date >= tempAccountNormatives.StartDate ) AND
						( Tariffs.Date < tempAccountNormatives.EndDate );


				CREATE TEMPORARY TABLE tempTariffsNormatives
					SELECT 
						prevTariffsNormatives.Id AS Id,
						prevTariffsNormatives.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextTariffsNormatives.StartDate
							FROM 
								prevTariffsNormatives AS nextTariffsNormatives
							WHERE
								( prevTariffsNormatives.StartDate < nextTariffsNormatives.StartDate ) AND
								( prevTariffsNormatives.Id = nextTariffsNormatives.Id )
							ORDER BY
								prevTariffsNormatives.Id,
								prevTariffsNormatives.StartDate
							LIMIT 1 ), prevTariffsNormatives.EndDate ) AS EndDate,
						prevTariffsNormatives.AccountId AS AccountId,
						prevTariffsNormatives.ServiceId AS ServiceId,
						prevTariffsNormatives.TariffId AS TariffId,
						prevTariffsNormatives.Portion AS Portion,
						prevTariffsNormatives.Сoefficient AS Сoefficient,
						prevTariffsNormatives.Count AS Count,
						prevTariffsNormatives.Price AS Price
					FROM 
						prevTariffsNormatives AS prevTariffsNormatives
					ORDER BY
						prevTariffsNormatives.Id,
						prevTariffsNormatives.StartDate;


				DELETE 
				FROM Profit
				WHERE 
					( Period = @start_date ) AND 
					( AccountId IN ( SELECT 
										 tempPersonalAccounts.AccountId AS AccountID 
									 FROM 
										 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
					( CalculationType = @calculation_type );
										 

				INSERT INTO Profit ( Period, StartDate, EndDate, AccountID, ServiceId, TariffId, CalculationType, Portion, Сoefficient, NormativeCount, Price, Summa )
					SELECT 
						@start_date AS Period,
						Result.StartDate AS StartDate,
						Result.EndDate AS EndDate,
						Result.AccountId AS AccountId,
						Result.ServiceId AS ServiceId,
						Result.TariffId AS TariffId,
						@calculation_type AS CalculationType,
						Result.Portion AS Portion,
						Result.Сoefficient AS Сoefficient,
						Result.Count AS Count,
						Result.Price AS Price,
						Result.Summa AS Summa
					FROM
						( SELECT 
							tempTariffsNormatives.StartDate AS StartDate,
							tempTariffsNormatives.EndDate AS EndDate,
							tempTariffsNormatives.AccountId AS AccountId,
							tempTariffsNormatives.ServiceId AS ServiceId,
							tempTariffsNormatives.TariffId AS TariffId,
							tempTariffsNormatives.Portion AS Portion,
							tempTariffsNormatives.Сoefficient AS Сoefficient,
							tempTariffsNormatives.Count AS Count,
							tempTariffsNormatives.Price AS Price,
							ROUND( tempTariffsNormatives.Count * tempTariffsNormatives.Price * tempTariffsNormatives.Portion * ( 1 + tempTariffsNormatives.Сoefficient ) * DATEDIFF( tempTariffsNormatives.StartDate, tempTariffsNormatives.EndDate ) / DATEDIFF( @start_date, @end_date ), 2 ) AS Summa
						FROM
							tempTariffsNormatives AS tempTariffsNormatives ) AS Result
					WHERE
						( Result.Summa <> 0 );";
			
	//echo '1. По нормативам потребления<br>';
	$mysqli = mysqli_connect( $host, $user, $password, $database ) or die( "Ошибка: " . mysqli_error( $mysqli ) );
	
	if ( mysqli_multi_query( $mysqli, $query ) or die( "Ошибка: " . mysqli_error( $mysqli ) ) ) { 
		do {
			if ( $result = mysqli_store_result( $mysqli ) ) {
				mysqli_free_result( $result );
			}
		} while ( mysqli_more_results( $mysqli ) && mysqli_next_result( $mysqli ) );
	}
	
	mysqli_close( $mysqli );
	
	// По показаниям приборов учета
	$query = "	SET @company_id       := " . $CompanyId . ";
				SET @calculation_type := 2;
				SET @start_date       := '" . $Period . "';
				SET @start_date       := LAST_DAY( @start_date ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND;
				SET @end_date         := DATE_ADD( @start_date, INTERVAL 1 MONTH );
				SET @id               := 0;


				CREATE TEMPORARY TABLE tempPersonalAccounts
					SELECT
						PersonalAccounts.AccountId AS AccountId
					FROM 
						PersonalAccounts AS PersonalAccounts
					WHERE
						( PersonalAccounts.CompanyId = @company_id ) AND
						( IFNULL( PersonalAccounts.Using, 0 ) = 1 );


				CREATE TEMPORARY TABLE prevAccountServices
					SELECT 
						@start_date AS Date,
						AccountServicesMax.AccountId AS AccountId,
						AccountServicesMax.ServiceId AS ServiceId,
						AccountServicesMax.TariffId AS TariffId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient
					FROM ( 
						SELECT 
							MAX( AccountServices.Date ) AS DateMax,
							AccountServices.AccountId AS AccountId,
							AccountServices.ServiceId AS ServiceId,
							AccountServices.TariffId AS TariffId
						FROM 
							AccountServices AS AccountServices
						WHERE 
							( AccountServices.Date < @start_date ) AND 
							( AccountServices.AccountId IN ( SELECT 
																 tempPersonalAccounts.AccountId AS AccountID 
															 FROM 
																 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
							( AccountServices.CalculationType = @calculation_type )
						GROUP BY
							AccountServices.AccountId,
							AccountServices.ServiceId,
							AccountServices.TariffId,
							AccountServices.CalculationType ) AS AccountServicesMax
					LEFT JOIN AccountServices AS AccountServices ON 
						( AccountServices.Date = AccountServicesMax.DateMax ) AND
						( AccountServices.AccountId = AccountServicesMax.AccountId ) AND
						( AccountServices.ServiceId = AccountServicesMax.ServiceId ) AND
						( AccountServices.TariffId = AccountServicesMax.TariffId ) AND
						( AccountServices.CalculationType = @calculation_type )

					UNION ALL

					SELECT 
						AccountServices.Date AS Date,
						AccountServices.AccountId AS AccountId,
						AccountServices.ServiceId AS ServiceId,
						AccountServices.TariffId AS TariffId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient
					FROM 
						AccountServices AS AccountServices
					WHERE 
						( AccountServices.Date >= @start_date ) AND 
						( AccountServices.Date < @end_date ) AND 
						( AccountServices.AccountId IN ( SELECT 
															 tempPersonalAccounts.AccountId AS AccountID 
														 FROM 
															 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
						( AccountServices.CalculationType = @calculation_type );


				CREATE TEMPORARY TABLE tempAccountServices
					SELECT 
						@id := @id + 1 AS Id,
						prevAccountServices.Date AS StartDate,
						IFNULL( ( SELECT 
								nextAccountServices.Date
							FROM 
								prevAccountServices AS nextAccountServices
							WHERE
								( prevAccountServices.Date < nextAccountServices.Date ) AND
								( prevAccountServices.AccountId = nextAccountServices.AccountId ) AND
								( prevAccountServices.ServiceId = nextAccountServices.ServiceId ) AND
								( prevAccountServices.TariffId = nextAccountServices.TariffId )
							ORDER BY
								prevAccountServices.AccountId,
								prevAccountServices.ServiceId,
								prevAccountServices.TariffId,
								prevAccountServices.Date
							LIMIT 1 ), @end_date ) AS EndDate,
						prevAccountServices.AccountId AS AccountId,
						prevAccountServices.ServiceId AS ServiceId,
						prevAccountServices.TariffId AS TariffId,
						prevAccountServices.Portion AS Portion,
						prevAccountServices.Сoefficient AS Сoefficient
					FROM 
						prevAccountServices AS prevAccountServices
					LEFT JOIN TariffTypes AS TariffTypes ON 
						( TariffTypes.TariffId = prevAccountServices.TariffId )
					WHERE
						( IFNULL( TariffTypes.IsNormative, 1 ) = 0 )
					ORDER BY
						prevAccountServices.AccountId,
						prevAccountServices.ServiceId,
						prevAccountServices.TariffId,
						prevAccountServices.Date;


				CREATE TEMPORARY TABLE prevAccountDevices
					SELECT 
						tempAccountServices.Id AS Id,
						IFNULL( AccountDevices.Date, tempAccountServices.StartDate ) AS StartDate,
						tempAccountServices.EndDate AS EndDate,
						tempAccountServices.AccountId AS AccountId,
						tempAccountServices.ServiceId AS ServiceId,
						tempAccountServices.TariffId AS TariffId,
						tempAccountServices.Portion AS Portion,
						tempAccountServices.Сoefficient AS Сoefficient,
						AccountDevices.DeviceId AS DeviceId
					FROM 
						tempAccountServices AS tempAccountServices
					LEFT JOIN 
						( SELECT 
							AccountDevicesMax.Id AS Id,
							AccountDevicesMax.StartDate AS Date,
							AccountDevicesMax.AccountId AS AccountId,
							AccountDevicesMax.ServiceId AS ServiceId,
							AccountDevicesMax.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( AccountDevices.Using, 0 ) = 1 ) THEN AccountDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							( SELECT 
								tempAccountServices.Id AS Id,
								tempAccountServices.StartDate AS StartDate,
								AccountDevices.AccountId AS AccountId,
								AccountDevices.ServiceId AS ServiceId,
								AccountDevices.TariffId AS TariffId,
								MAX( AccountDevices.Date ) AS DateMax
							FROM 
								tempAccountServices AS tempAccountServices
							LEFT JOIN AccountDevices AS AccountDevices ON 
								( AccountDevices.Date <= tempAccountServices.StartDate ) AND
								( AccountDevices.AccountId = tempAccountServices.AccountId ) AND
								( AccountDevices.ServiceId = tempAccountServices.ServiceId ) AND
								( AccountDevices.TariffId = tempAccountServices.TariffId )
							WHERE 
								( AccountDevices.AccountId IN ( SELECT 
																	tempAccountServices.AccountId AS AccountID 
																FROM 
																	tempAccountServices AS tempAccountServices ) )
							GROUP BY
								tempAccountServices.Id,
								tempAccountServices.StartDate,
								AccountDevices.AccountId,
								AccountDevices.ServiceId,
								AccountDevices.TariffId ) AS AccountDevicesMax
						LEFT JOIN AccountDevices AS AccountDevices ON 
							( AccountDevices.AccountId = AccountDevicesMax.AccountId ) AND
							( AccountDevices.ServiceId = AccountDevicesMax.ServiceId ) AND
							( AccountDevices.TariffId = AccountDevicesMax.TariffId ) AND
							( AccountDevices.Date = AccountDevicesMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountServices.Id AS Id,
							AccountDevices.Date AS Date,
							AccountDevices.AccountId AS AccountId,
							AccountDevices.ServiceId AS ServiceId,
							AccountDevices.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( AccountDevices.Using, 0 ) = 1 ) THEN AccountDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							tempAccountServices AS tempAccountServices
						LEFT JOIN AccountDevices AS AccountDevices ON 
							( AccountDevices.Date > tempAccountServices.StartDate ) AND
							( AccountDevices.Date <= tempAccountServices.EndDate ) AND
							( AccountDevices.AccountId = tempAccountServices.AccountId ) AND
							( AccountDevices.ServiceId = tempAccountServices.ServiceId ) AND
							( AccountDevices.TariffId = tempAccountServices.TariffId )
						WHERE 
							( AccountDevices.AccountId IN ( SELECT 
																tempAccountServices.AccountId AS AccountID 
															FROM 
																tempAccountServices AS tempAccountServices ) ) ) AS AccountDevices ON 
						( AccountDevices.Id = tempAccountServices.Id ) AND
						( AccountDevices.Date >= tempAccountServices.StartDate ) AND
						( AccountDevices.Date < tempAccountServices.EndDate );


				CREATE TEMPORARY TABLE tempAccountDevices
					SELECT 
						prevAccountDevices.Id AS Id,
						prevAccountDevices.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextAccountDevices.StartDate
							FROM 
								prevAccountDevices AS nextAccountDevices
							WHERE
								( prevAccountDevices.StartDate < nextAccountDevices.StartDate ) AND
								( prevAccountDevices.Id = nextAccountDevices.Id )
							ORDER BY
								prevAccountDevices.Id,
								prevAccountDevices.StartDate
							LIMIT 1 ), prevAccountDevices.EndDate ) AS EndDate,
						prevAccountDevices.AccountId AS AccountId,
						prevAccountDevices.ServiceId AS ServiceId,
						prevAccountDevices.TariffId AS TariffId,
						prevAccountDevices.Portion AS Portion,
						prevAccountDevices.Сoefficient AS Сoefficient,
						prevAccountDevices.DeviceId AS DeviceId
					FROM 
						prevAccountDevices AS prevAccountDevices
					ORDER BY
						prevAccountDevices.Id,
						prevAccountDevices.StartDate;


				CREATE TEMPORARY TABLE prevTariffsDevices
					SELECT 
						tempAccountDevices.Id AS Id,
						IFNULL( Tariffs.Date, tempAccountDevices.StartDate ) AS StartDate,
						tempAccountDevices.EndDate AS EndDate,
						tempAccountDevices.AccountId AS AccountId,
						tempAccountDevices.ServiceId AS ServiceId,
						tempAccountDevices.TariffId AS TariffId,
						tempAccountDevices.Portion AS Portion,
						tempAccountDevices.Сoefficient AS Сoefficient,
						tempAccountDevices.DeviceId AS DeviceId,
						IFNULL( Tariffs.Price, 0 ) AS Price
					FROM 
						tempAccountDevices AS tempAccountDevices
					LEFT JOIN 
						( SELECT 
							TariffsMax.Id AS Id,
							TariffsMax.StartDate AS Date,
							TariffsMax.ServiceId AS ServiceId,
							TariffsMax.TariffId AS TariffId,
							IFNULL( Tariffs.Price, 0 ) AS Price
						FROM 
							( SELECT 
								tempAccountDevices.Id AS Id,
								tempAccountDevices.StartDate AS StartDate,
								Tariffs.ServiceId AS ServiceId,
								Tariffs.TariffId AS TariffId,
								MAX( Tariffs.Date ) AS DateMax
							FROM 
								tempAccountDevices AS tempAccountDevices
							LEFT JOIN Tariffs AS Tariffs ON 
								( Tariffs.Date <= tempAccountDevices.StartDate ) AND
								( Tariffs.CompanyId = @company_id ) AND
								( Tariffs.ServiceId = tempAccountDevices.ServiceId ) AND
								( Tariffs.TariffId = tempAccountDevices.TariffId )
							WHERE 
								( Tariffs.ServiceId IN ( SELECT 
															 tempAccountDevices.ServiceId AS ServiceId 
														 FROM 
															 tempAccountDevices AS tempAccountDevices ) )
							GROUP BY
								tempAccountDevices.Id,
								tempAccountDevices.StartDate,
								Tariffs.ServiceId,
								Tariffs.TariffId ) AS TariffsMax
						LEFT JOIN Tariffs AS Tariffs ON 
							( Tariffs.CompanyId = @company_id ) AND
							( Tariffs.ServiceId = TariffsMax.ServiceId ) AND
							( Tariffs.TariffId = TariffsMax.TariffId ) AND
							( Tariffs.Date = TariffsMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountDevices.Id AS Id,
							Tariffs.Date AS Date,
							Tariffs.ServiceId AS ServiceId,
							Tariffs.TariffId AS TariffId,
							IFNULL( Tariffs.Price, 0 ) AS Price
						FROM 
							tempAccountDevices AS tempAccountDevices
						LEFT JOIN Tariffs AS Tariffs ON 
							( Tariffs.Date > tempAccountDevices.StartDate ) AND
							( Tariffs.Date <= tempAccountDevices.EndDate ) AND
							( Tariffs.CompanyId = @company_id ) AND
							( Tariffs.ServiceId = tempAccountDevices.ServiceId ) AND
							( Tariffs.TariffId = tempAccountDevices.TariffId )
						WHERE 
							( Tariffs.ServiceId IN ( SELECT 
														 tempAccountDevices.ServiceId AS ServiceId 
													 FROM 
														 tempAccountDevices AS tempAccountDevices ) ) ) AS Tariffs ON 
						( Tariffs.Id = tempAccountDevices.Id ) AND
						( Tariffs.Date >= tempAccountDevices.StartDate ) AND
						( Tariffs.Date < tempAccountDevices.EndDate );


				CREATE TEMPORARY TABLE tempTariffsDevices
					SELECT 
						prevTariffsDevices.Id AS Id,
						prevTariffsDevices.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextTariffsDevices.StartDate
							FROM 
								prevTariffsDevices AS nextTariffsDevices
							WHERE
								( prevTariffsDevices.StartDate < nextTariffsDevices.StartDate ) AND
								( prevTariffsDevices.Id = nextTariffsDevices.Id )
							ORDER BY
								prevTariffsDevices.Id,
								prevTariffsDevices.StartDate
							LIMIT 1 ), prevTariffsDevices.EndDate ) AS EndDate,
						prevTariffsDevices.AccountId AS AccountId,
						prevTariffsDevices.ServiceId AS ServiceId,
						prevTariffsDevices.TariffId AS TariffId,
						prevTariffsDevices.Portion AS Portion,
						prevTariffsDevices.Сoefficient AS Сoefficient,
						prevTariffsDevices.DeviceId AS DeviceId,
						prevTariffsDevices.Price AS Price
					FROM 
						prevTariffsDevices AS prevTariffsDevices
					ORDER BY
						prevTariffsDevices.Id,
						prevTariffsDevices.StartDate;


				CREATE TEMPORARY TABLE tempDeviceIndications
					SELECT 
						tempTariffsDevices.Id AS Id,
						tempTariffsDevices.StartDate AS StartDate,
						tempTariffsDevices.EndDate AS EndDate,
						tempTariffsDevices.AccountId AS AccountId,
						tempTariffsDevices.ServiceId AS ServiceId,
						tempTariffsDevices.TariffId AS TariffId,
						tempTariffsDevices.Portion AS Portion,
						tempTariffsDevices.Сoefficient AS Сoefficient,
						tempTariffsDevices.DeviceId AS DeviceId,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempTariffsDevices.StartDate ) AND
								( DeviceIndications.DeviceId = tempTariffsDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS PreviousIndications,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempTariffsDevices.EndDate ) AND
								( DeviceIndications.DeviceId = tempTariffsDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS CurrentIndications,
						tempTariffsDevices.Price AS Price
					FROM 
						tempTariffsDevices AS tempTariffsDevices;


				DELETE 
				FROM Profit
				WHERE 
					( Period = @start_date ) AND 
					( AccountId IN ( SELECT 
										 tempPersonalAccounts.AccountId AS AccountID 
									 FROM 
										 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
					( CalculationType = @calculation_type );
										 

				INSERT INTO Profit ( Period, StartDate, EndDate, AccountID, ServiceId, TariffId, CalculationType, Portion, Сoefficient, DeviceId, PreviousIndications, CurrentIndications, Indications, Price, Summa )
					SELECT 
						@start_date AS Period,
						Result.StartDate AS StartDate,
						Result.EndDate AS EndDate,
						Result.AccountId AS AccountId,
						Result.ServiceId AS ServiceId,
						Result.TariffId AS TariffId,
						@calculation_type AS CalculationType,
						Result.Portion AS Portion,
						Result.Сoefficient AS Сoefficient,
						Result.DeviceId AS DeviceId,
						Result.PreviousIndications AS PreviousIndications,
						Result.CurrentIndications AS CurrentIndications,
						Result.Indications AS Indications,
						Result.Price AS Price,
						Result.Summa AS Summa
					FROM
						( SELECT 
							tempDeviceIndications.StartDate AS StartDate,
							tempDeviceIndications.EndDate AS EndDate,
							tempDeviceIndications.AccountId AS AccountId,
							tempDeviceIndications.ServiceId AS ServiceId,
							tempDeviceIndications.TariffId AS TariffId,
							tempDeviceIndications.Portion AS Portion,
							tempDeviceIndications.Сoefficient AS Сoefficient,
							tempDeviceIndications.DeviceId AS DeviceId,
							tempDeviceIndications.PreviousIndications AS PreviousIndications,
							tempDeviceIndications.CurrentIndications AS CurrentIndications,
							tempDeviceIndications.CurrentIndications - tempDeviceIndications.PreviousIndications AS Indications,
							tempDeviceIndications.Price AS Price,
							ROUND( ( tempDeviceIndications.CurrentIndications - tempDeviceIndications.PreviousIndications ) * tempDeviceIndications.Price * tempDeviceIndications.Portion * ( 1 + tempDeviceIndications.Сoefficient ) * DATEDIFF( tempDeviceIndications.StartDate, tempDeviceIndications.EndDate ) / DATEDIFF( @start_date, @end_date ), 2 ) AS Summa
						FROM
							tempDeviceIndications AS tempDeviceIndications ) AS Result
					WHERE
						( Result.Summa <> 0 );


				UPDATE 
					DeviceIndications 
				SET 
					Fixed = 1
				WHERE 
					( DeviceId IN ( SELECT 
										tempDeviceIndications.DeviceId AS DeviceId 
									FROM 
										tempDeviceIndications AS tempDeviceIndications ) ) AND
					( Date <= @end_date ) AND
					( Fixed = 0 );";
	
	//echo '2. По показаниям приборов учета<br>';
	$mysqli = mysqli_connect( $host, $user, $password, $database ) or die( "Ошибка: " . mysqli_error( $mysqli ) );
	
	if ( mysqli_multi_query( $mysqli, $query ) or die( "Ошибка: " . mysqli_error( $mysqli ) ) ) { 
		do {
			if ( $result = mysqli_store_result( $mysqli ) ) {
				mysqli_free_result( $result );
			}
		} while ( mysqli_more_results( $mysqli ) && mysqli_next_result( $mysqli ) );
	}
	
	mysqli_close( $mysqli );
		
	// Фиксированные суммы
	$query = "	SET @company_id       := " . $CompanyId . ";
				SET @calculation_type := 3;
				SET @start_date       := '" . $Period . "';
				SET @start_date       := LAST_DAY( @start_date ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND;
				SET @end_date         := DATE_ADD( @start_date, INTERVAL 1 MONTH );
				SET @id               := 0;


				CREATE TEMPORARY TABLE tempPersonalAccounts
					SELECT
						PersonalAccounts.AccountId AS AccountId
					FROM 
						PersonalAccounts AS PersonalAccounts
					WHERE
						( PersonalAccounts.CompanyId = @company_id ) AND
						( IFNULL( PersonalAccounts.Using, 0 ) = 1 );


				CREATE TEMPORARY TABLE prevAccountServices
					SELECT 
						@start_date AS Date,
						AccountServicesMax.AccountId AS AccountId,
						AccountServicesMax.ServiceId AS ServiceId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient
					FROM ( 
						SELECT 
							MAX( AccountServices.Date ) AS DateMax,
							AccountServices.AccountId AS AccountId,
							AccountServices.ServiceId AS ServiceId
						FROM 
							AccountServices AS AccountServices
						WHERE 
							( AccountServices.Date < @start_date ) AND 
							( AccountServices.AccountId IN ( SELECT 
																 tempPersonalAccounts.AccountId AS AccountID 
															 FROM 
																 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
							( AccountServices.CalculationType = @calculation_type )
						GROUP BY
							AccountServices.AccountId,
							AccountServices.ServiceId,
							AccountServices.CalculationType ) AS AccountServicesMax
					LEFT JOIN AccountServices AS AccountServices ON 
						( AccountServices.Date = AccountServicesMax.DateMax ) AND
						( AccountServices.AccountId = AccountServicesMax.AccountId ) AND
						( AccountServices.ServiceId = AccountServicesMax.ServiceId ) AND
						( AccountServices.CalculationType = @calculation_type )

					UNION ALL

					SELECT 
						AccountServices.Date AS Date,
						AccountServices.AccountId AS AccountId,
						AccountServices.ServiceId AS ServiceId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient
					FROM 
						AccountServices AS AccountServices
					WHERE 
						( AccountServices.Date >= @start_date ) AND 
						( AccountServices.Date < @end_date ) AND 
						( AccountServices.AccountId IN ( SELECT 
															 tempPersonalAccounts.AccountId AS AccountID 
														 FROM 
															 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
						( AccountServices.CalculationType = @calculation_type );


				CREATE TEMPORARY TABLE tempAccountServices
					SELECT 
						@id := @id + 1 AS Id,
						prevAccountServices.Date AS StartDate,
						IFNULL( ( SELECT 
								nextAccountServices.Date
							FROM 
								prevAccountServices AS nextAccountServices
							WHERE
								( prevAccountServices.Date < nextAccountServices.Date ) AND
								( prevAccountServices.AccountId = nextAccountServices.AccountId ) AND
								( prevAccountServices.ServiceId = nextAccountServices.ServiceId )
							ORDER BY
								prevAccountServices.AccountId,
								prevAccountServices.ServiceId,
								prevAccountServices.Date
							LIMIT 1 ), @end_date ) AS EndDate,
						prevAccountServices.AccountId AS AccountId,
						prevAccountServices.ServiceId AS ServiceId,
						prevAccountServices.Portion AS Portion,
						prevAccountServices.Сoefficient AS Сoefficient
					FROM 
						prevAccountServices AS prevAccountServices
					ORDER BY
						prevAccountServices.AccountId,
						prevAccountServices.ServiceId,
						prevAccountServices.Date;


				CREATE TEMPORARY TABLE prevAccountNormatives
					SELECT 
						tempAccountServices.Id AS Id,
						IFNULL( AccountNormatives.Date, tempAccountServices.StartDate ) AS StartDate,
						tempAccountServices.EndDate AS EndDate,
						tempAccountServices.AccountId AS AccountId,
						tempAccountServices.ServiceId AS ServiceId,
						tempAccountServices.Portion AS Portion,
						tempAccountServices.Сoefficient AS Сoefficient,
						IFNULL( AccountNormatives.Count, 0 ) AS Count
					FROM 
						tempAccountServices AS tempAccountServices
					LEFT JOIN 
						( SELECT 
							AccountNormativesMax.Id AS Id,
							AccountNormativesMax.StartDate AS Date,
							AccountNormativesMax.AccountId AS AccountId,
							AccountNormativesMax.ServiceId AS ServiceId,
							CASE 
								WHEN ( IFNULL( AccountNormatives.Using, 0 ) = 1 ) THEN IFNULL( AccountNormatives.Count, 0 )
								ELSE 0
							END AS Count
						FROM 
							( SELECT 
								tempAccountServices.Id AS Id,
								tempAccountServices.StartDate AS StartDate,
								AccountNormatives.AccountId AS AccountId,
								AccountNormatives.ServiceId AS ServiceId,
								MAX( AccountNormatives.Date ) AS DateMax
							FROM 
								tempAccountServices AS tempAccountServices
							LEFT JOIN AccountNormatives AS AccountNormatives ON 
								( AccountNormatives.Date <= tempAccountServices.StartDate ) AND
								( AccountNormatives.AccountId = tempAccountServices.AccountId ) AND
								( AccountNormatives.ServiceId = tempAccountServices.ServiceId )
							WHERE 
								( AccountNormatives.AccountId IN ( SELECT 
																	   tempAccountServices.AccountId AS AccountID 
																   FROM 
																	   tempAccountServices AS tempAccountServices ) )
							GROUP BY
								tempAccountServices.Id,
								tempAccountServices.StartDate,
								AccountNormatives.AccountId,
								AccountNormatives.ServiceId ) AS AccountNormativesMax
						LEFT JOIN AccountNormatives AS AccountNormatives ON 
							( AccountNormatives.AccountId = AccountNormativesMax.AccountId ) AND
							( AccountNormatives.ServiceId = AccountNormativesMax.ServiceId ) AND
							( AccountNormatives.Date = AccountNormativesMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountServices.Id AS Id,
							AccountNormatives.Date AS Date,
							AccountNormatives.AccountId AS AccountId,
							AccountNormatives.ServiceId AS ServiceId,
							CASE 
								WHEN ( IFNULL( AccountNormatives.Using, 0 ) = 1 ) THEN IFNULL( AccountNormatives.Count, 0 )
								ELSE 0
							END AS Count
						FROM 
							tempAccountServices AS tempAccountServices
						LEFT JOIN AccountNormatives AS AccountNormatives ON 
							( AccountNormatives.Date > tempAccountServices.StartDate ) AND
							( AccountNormatives.Date <= tempAccountServices.EndDate ) AND
							( AccountNormatives.AccountId = tempAccountServices.AccountId ) AND
							( AccountNormatives.ServiceId = tempAccountServices.ServiceId ) 
						WHERE 
							( AccountNormatives.AccountId IN ( SELECT 
																   tempAccountServices.AccountId AS AccountID 
															   FROM 
																   tempAccountServices AS tempAccountServices ) ) ) AS AccountNormatives ON 
						( AccountNormatives.Id = tempAccountServices.Id ) AND
						( AccountNormatives.Date >= tempAccountServices.StartDate ) AND
						( AccountNormatives.Date < tempAccountServices.EndDate );


				CREATE TEMPORARY TABLE tempAccountNormatives
					SELECT 
						prevAccountNormatives.Id AS Id,
						prevAccountNormatives.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextAccountNormatives.StartDate
							FROM 
								prevAccountNormatives AS nextAccountNormatives
							WHERE
								( prevAccountNormatives.StartDate < nextAccountNormatives.StartDate ) AND
								( prevAccountNormatives.Id = nextAccountNormatives.Id )
							ORDER BY
								prevAccountNormatives.Id,
								prevAccountNormatives.StartDate
							LIMIT 1 ), prevAccountNormatives.EndDate ) AS EndDate,
						prevAccountNormatives.AccountId AS AccountId,
						prevAccountNormatives.ServiceId AS ServiceId,
						prevAccountNormatives.Portion AS Portion,
						prevAccountNormatives.Сoefficient AS Сoefficient,
						prevAccountNormatives.Count AS Count
					FROM 
						prevAccountNormatives AS prevAccountNormatives
					ORDER BY
						prevAccountNormatives.Id,
						prevAccountNormatives.StartDate;


				DELETE 
				FROM Profit
				WHERE 
					( Period = @start_date ) AND 
					( AccountId IN ( SELECT 
										 tempPersonalAccounts.AccountId AS AccountID 
									 FROM 
										 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
					( CalculationType = @calculation_type );
										 

				INSERT INTO Profit ( Period, StartDate, EndDate, AccountID, ServiceId, CalculationType, Portion, Сoefficient, FixedSumma, Price, Summa )
					SELECT 
						@start_date AS Period,
						Result.StartDate AS StartDate,
						Result.EndDate AS EndDate,
						Result.AccountId AS AccountId,
						Result.ServiceId AS ServiceId,
						@calculation_type AS CalculationType,
						Result.Portion AS Portion,
						Result.Сoefficient AS Сoefficient,
						Result.Count AS Count,
						0 AS Price,
						Result.Summa AS Summa
					FROM
						( SELECT 
							tempAccountNormatives.StartDate AS StartDate,
							tempAccountNormatives.EndDate AS EndDate,
							tempAccountNormatives.AccountId AS AccountId,
							tempAccountNormatives.ServiceId AS ServiceId,
							tempAccountNormatives.Portion AS Portion,
							tempAccountNormatives.Сoefficient AS Сoefficient,
							tempAccountNormatives.Count AS Count,
							ROUND( tempAccountNormatives.Count * tempAccountNormatives.Portion * ( 1 + tempAccountNormatives.Сoefficient ) * DATEDIFF( tempAccountNormatives.StartDate, tempAccountNormatives.EndDate ) / DATEDIFF( @start_date, @end_date ), 2 ) AS Summa
						FROM
							tempAccountNormatives AS tempAccountNormatives ) AS Result
					WHERE
						( Result.Summa <> 0 );";

	//echo '3. Фиксированные суммы<br>';
	$mysqli = mysqli_connect( $host, $user, $password, $database ) or die( "Ошибка: " . mysqli_error( $mysqli ) );
	
	if ( mysqli_multi_query( $mysqli, $query ) or die( "Ошибка: " . mysqli_error( $mysqli ) ) ) { 
		do {
			if ( $result = mysqli_store_result( $mysqli ) ) {
				mysqli_free_result( $result );
			}
		} while ( mysqli_more_results( $mysqli ) && mysqli_next_result( $mysqli ) );
	}
	
	mysqli_close( $mysqli );
	
	// Пропорционально площадей объектов недвижимости
	$query = "	SET @company_id       := " . $CompanyId . ";
				SET @calculation_type := 4;
				SET @start_date       := '" . $Period . "';
				SET @start_date       := LAST_DAY( @start_date ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND;
				SET @end_date         := DATE_ADD( @start_date, INTERVAL 1 MONTH );
				SET @id               := 0;


				CREATE TEMPORARY TABLE tempPersonalAccounts
					SELECT
						PersonalAccounts.AccountId AS AccountId,
						Objects.ObjectId AS ObjectId,
						IFNULL( Objects.Square, 0 ) AS ObjectSquare,
						Objects.ParentId AS ParentId
					FROM Objects AS Objects
					LEFT JOIN PersonalAccounts AS PersonalAccounts ON 
						( PersonalAccounts.ObjectId = Objects.ObjectId )
					WHERE
						( PersonalAccounts.CompanyId = @company_id ) AND
						( IFNULL( PersonalAccounts.Using, 1 ) = 1 );


				CREATE TEMPORARY TABLE prevAccountServices
					SELECT 
						@start_date AS Date,
						AccountServicesMax.AccountId AS AccountId,
						AccountServicesMax.ServiceId AS ServiceId,
						AccountServicesMax.TariffId AS TariffId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient,
						tempPersonalAccounts.ObjectId AS ObjectId,
						IFNULL( tempPersonalAccounts.ObjectSquare, 0 ) AS ObjectSquare,
						tempPersonalAccounts.ParentId AS ParentId
					FROM ( 
						SELECT 
							MAX( AccountServices.Date ) AS DateMax,
							AccountServices.AccountId AS AccountId,
							AccountServices.ServiceId AS ServiceId,
							AccountServices.TariffId AS TariffId
						FROM 
							AccountServices AS AccountServices
						WHERE 
							( AccountServices.Date < @start_date ) AND 
							( AccountServices.AccountId IN ( SELECT 
																 tempPersonalAccounts.AccountId AS AccountID 
															 FROM 
																 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
							( AccountServices.CalculationType = @calculation_type )
						GROUP BY
							AccountServices.AccountId,
							AccountServices.ServiceId,
							AccountServices.TariffId,
							AccountServices.CalculationType ) AS AccountServicesMax
					LEFT JOIN AccountServices AS AccountServices ON 
						( AccountServices.Date = AccountServicesMax.DateMax ) AND
						( AccountServices.AccountId = AccountServicesMax.AccountId ) AND
						( AccountServices.ServiceId = AccountServicesMax.ServiceId ) AND
						( AccountServices.TariffId = AccountServicesMax.TariffId ) AND
						( AccountServices.CalculationType = @calculation_type )
					LEFT JOIN tempPersonalAccounts AS tempPersonalAccounts ON 
						( tempPersonalAccounts.AccountId = AccountServicesMax.AccountId )

					UNION ALL

					SELECT 
						AccountServices.Date AS Date,
						AccountServices.AccountId AS AccountId,
						AccountServices.ServiceId AS ServiceId,
						AccountServices.TariffId AS TariffId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient,
						tempPersonalAccounts.ObjectId AS ObjectId,
						IFNULL( tempPersonalAccounts.ObjectSquare, 0 ) AS ObjectSquare,
						tempPersonalAccounts.ParentId AS ParentId
					FROM 
						AccountServices AS AccountServices
					LEFT JOIN tempPersonalAccounts AS tempPersonalAccounts ON 
						( tempPersonalAccounts.AccountId = AccountServices.AccountId )
					WHERE 
						( AccountServices.Date >= @start_date ) AND 
						( AccountServices.Date < @end_date ) AND 
						( AccountServices.AccountId IN ( SELECT 
															 tempPersonalAccounts.AccountId AS AccountID 
														 FROM 
															 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
						( AccountServices.CalculationType = @calculation_type );


				CREATE TEMPORARY TABLE tempAccountServices
					SELECT 
						@id := @id + 1 AS Id,
						prevAccountServices.Date AS StartDate,
						IFNULL( ( SELECT 
								nextAccountServices.Date
							FROM 
								prevAccountServices AS nextAccountServices
							WHERE
								( prevAccountServices.Date < nextAccountServices.Date ) AND
								( prevAccountServices.AccountId = nextAccountServices.AccountId ) AND
								( prevAccountServices.ServiceId = nextAccountServices.ServiceId ) AND
								( prevAccountServices.TariffId = nextAccountServices.TariffId )
							ORDER BY
								prevAccountServices.AccountId,
								prevAccountServices.ServiceId,
								prevAccountServices.TariffId,
								prevAccountServices.Date
							LIMIT 1 ), @end_date ) AS EndDate,
						prevAccountServices.AccountId AS AccountId,
						prevAccountServices.ServiceId AS ServiceId,
						prevAccountServices.TariffId AS TariffId,
						prevAccountServices.Portion AS Portion,
						prevAccountServices.Сoefficient AS Сoefficient,
						prevAccountServices.ObjectId AS ObjectId,
						prevAccountServices.ObjectSquare AS ObjectSquare,
						prevAccountServices.ParentId AS ParentId,
						IFNULL( ( SELECT 
									  SUM( sumAccountServices.ObjectSquare ) 
								  FROM 
									  prevAccountServices AS sumAccountServices
								  WHERE
									  ( sumAccountServices.ParentId = prevAccountServices.ParentId ) ), 0 ) AS TotalSquare
					FROM 
						prevAccountServices AS prevAccountServices
					ORDER BY
						prevAccountServices.AccountId,
						prevAccountServices.ServiceId,
						prevAccountServices.TariffId,
						prevAccountServices.Date;


				CREATE TEMPORARY TABLE prevAccountDevices
					SELECT 
						tempAccountServices.Id AS Id,
						tempAccountServices.ParentId AS ParentId,
						IFNULL( AccountDevices.Date, tempAccountServices.StartDate ) AS StartDate,
						tempAccountServices.EndDate AS EndDate,
						tempAccountServices.AccountId AS AccountId,
						tempAccountServices.ServiceId AS ServiceId,
						tempAccountServices.TariffId AS TariffId,
						tempAccountServices.Portion AS Portion,
						tempAccountServices.Сoefficient AS Сoefficient,
						AccountDevices.DeviceId AS DeviceId
					FROM 
						tempAccountServices AS tempAccountServices
					LEFT JOIN 
						( SELECT 
							AccountDevicesMax.Id AS Id,
							AccountDevicesMax.StartDate AS Date,
							AccountDevicesMax.AccountId AS AccountId,
							AccountDevicesMax.ServiceId AS ServiceId,
							AccountDevicesMax.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( AccountDevices.Using, 0 ) = 1 ) THEN AccountDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							( SELECT 
								tempAccountServices.Id AS Id,
								tempAccountServices.StartDate AS StartDate,
								AccountDevices.AccountId AS AccountId,
								AccountDevices.ServiceId AS ServiceId,
								AccountDevices.TariffId AS TariffId,
								MAX( AccountDevices.Date ) AS DateMax
							FROM 
								tempAccountServices AS tempAccountServices
							LEFT JOIN AccountDevices AS AccountDevices ON 
								( AccountDevices.Date <= tempAccountServices.StartDate ) AND
								( AccountDevices.AccountId = tempAccountServices.AccountId ) AND
								( AccountDevices.ServiceId = tempAccountServices.ServiceId ) AND
								( AccountDevices.TariffId = tempAccountServices.TariffId )
							WHERE 
								( AccountDevices.AccountId IN ( SELECT 
																	tempAccountServices.AccountId AS AccountID 
																FROM 
																	tempAccountServices AS tempAccountServices ) )
							GROUP BY
								tempAccountServices.Id,
								tempAccountServices.StartDate,
								AccountDevices.AccountId,
								AccountDevices.ServiceId,
								AccountDevices.TariffId ) AS AccountDevicesMax
						LEFT JOIN AccountDevices AS AccountDevices ON 
							( AccountDevices.AccountId = AccountDevicesMax.AccountId ) AND
							( AccountDevices.ServiceId = AccountDevicesMax.ServiceId ) AND
							( AccountDevices.TariffId = AccountDevicesMax.TariffId ) AND
							( AccountDevices.Date = AccountDevicesMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountServices.Id AS Id,
							AccountDevices.Date AS Date,
							AccountDevices.AccountId AS AccountId,
							AccountDevices.ServiceId AS ServiceId,
							AccountDevices.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( AccountDevices.Using, 0 ) = 1 ) THEN AccountDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							tempAccountServices AS tempAccountServices
						LEFT JOIN AccountDevices AS AccountDevices ON 
							( AccountDevices.Date > tempAccountServices.StartDate ) AND
							( AccountDevices.Date <= tempAccountServices.EndDate ) AND
							( AccountDevices.AccountId = tempAccountServices.AccountId ) AND
							( AccountDevices.ServiceId = tempAccountServices.ServiceId ) AND
							( AccountDevices.TariffId = tempAccountServices.TariffId )
						WHERE 
							( AccountDevices.AccountId IN ( SELECT 
																tempAccountServices.AccountId AS AccountID 
															FROM 
																tempAccountServices AS tempAccountServices ) ) ) AS AccountDevices ON 
						( AccountDevices.Id = tempAccountServices.Id ) AND
						( AccountDevices.Date >= tempAccountServices.StartDate ) AND
						( AccountDevices.Date < tempAccountServices.EndDate );


				CREATE TEMPORARY TABLE tempAccountDevices
					SELECT 
						prevAccountDevices.Id AS Id,
						prevAccountDevices.ParentId AS ParentId,
						prevAccountDevices.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextAccountDevices.StartDate
							FROM 
								prevAccountDevices AS nextAccountDevices
							WHERE
								( prevAccountDevices.StartDate < nextAccountDevices.StartDate ) AND
								( prevAccountDevices.Id = nextAccountDevices.Id )
							ORDER BY
								prevAccountDevices.Id,
								prevAccountDevices.StartDate
							LIMIT 1 ), prevAccountDevices.EndDate ) AS EndDate,
						prevAccountDevices.AccountId AS AccountId,
						prevAccountDevices.ServiceId AS ServiceId,
						prevAccountDevices.TariffId AS TariffId,
						prevAccountDevices.Portion AS Portion,
						prevAccountDevices.Сoefficient AS Сoefficient,
						prevAccountDevices.DeviceId AS DeviceId
					FROM 
						prevAccountDevices AS prevAccountDevices
					ORDER BY
						prevAccountDevices.Id,
						prevAccountDevices.StartDate;


				CREATE TEMPORARY TABLE tempDeviceIndications
					SELECT 
						tempAccountDevices.Id AS Id,
						tempAccountDevices.ParentId AS ParentId,
						tempAccountDevices.StartDate AS StartDate,
						tempAccountDevices.EndDate AS EndDate,
						tempAccountDevices.AccountId AS AccountId,
						tempAccountDevices.ServiceId AS ServiceId,
						tempAccountDevices.TariffId AS TariffId,
						tempAccountDevices.Portion AS Portion,
						tempAccountDevices.Сoefficient AS Сoefficient,
						tempAccountDevices.DeviceId AS DeviceId,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempAccountDevices.StartDate ) AND
								( DeviceIndications.DeviceId = tempAccountDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS PreviousIndications,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempAccountDevices.EndDate ) AND
								( DeviceIndications.DeviceId = tempAccountDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS CurrentIndications,
						DATEDIFF( tempAccountDevices.StartDate, tempAccountDevices.EndDate ) / DATEDIFF( @start_date, @end_date ) AS DevicePortion
					FROM 
						tempAccountDevices AS tempAccountDevices;


				CREATE TEMPORARY TABLE prevParentDevices
					SELECT 
						tempAccountServices.Id AS Id,
						tempAccountServices.ParentId AS ParentId,
						IFNULL( CommonDevices.Date, tempAccountServices.StartDate ) AS StartDate,
						tempAccountServices.EndDate AS EndDate,
						tempAccountServices.ServiceId AS ServiceId,
						tempAccountServices.TariffId AS TariffId,
						tempAccountServices.Portion AS Portion,
						tempAccountServices.Сoefficient AS Сoefficient,
						CommonDevices.DeviceId AS DeviceId
					FROM 
						tempAccountServices AS tempAccountServices
					LEFT JOIN 
						( SELECT 
							CommonDevicesMax.Id AS Id,
							CommonDevicesMax.StartDate AS Date,
							CommonDevicesMax.ParentId AS ParentId,
							CommonDevicesMax.ServiceId AS ServiceId,
							CommonDevicesMax.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( CommonDevices.Using, 0 ) = 1 ) THEN CommonDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							( SELECT 
								tempAccountServices.Id AS Id,
								tempAccountServices.StartDate AS StartDate,
								CommonDevices.ObjectId AS ParentId,
								CommonDevices.ServiceId AS ServiceId,
								CommonDevices.TariffId AS TariffId,
								MAX( CommonDevices.Date ) AS DateMax
							FROM 
								tempAccountServices AS tempAccountServices
							LEFT JOIN CommonDevices AS CommonDevices ON 
								( CommonDevices.Date <= tempAccountServices.StartDate ) AND
								( CommonDevices.ObjectId = tempAccountServices.ParentId ) AND
								( CommonDevices.ServiceId = tempAccountServices.ServiceId ) AND
								( CommonDevices.TariffId = tempAccountServices.TariffId )
							WHERE 
								( CommonDevices.ObjectId IN ( SELECT 
																  tempAccountServices.ParentId AS ParentId 
															  FROM 
																  tempAccountServices AS tempAccountServices ) )
							GROUP BY
								tempAccountServices.Id,
								tempAccountServices.StartDate,
								CommonDevices.ObjectId,
								CommonDevices.ServiceId,
								CommonDevices.TariffId ) AS CommonDevicesMax
						LEFT JOIN CommonDevices AS CommonDevices ON 
							( CommonDevices.ObjectId = CommonDevicesMax.ParentId ) AND
							( CommonDevices.ServiceId = CommonDevicesMax.ServiceId ) AND
							( CommonDevices.TariffId = CommonDevicesMax.TariffId ) AND
							( CommonDevices.Date = CommonDevicesMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountServices.Id AS Id,
							CommonDevices.Date AS Date,
							CommonDevices.ObjectId AS ParentId,
							CommonDevices.ServiceId AS ServiceId,
							CommonDevices.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( CommonDevices.Using, 0 ) = 1 ) THEN CommonDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							tempAccountServices AS tempAccountServices
						LEFT JOIN CommonDevices AS CommonDevices ON 
							( CommonDevices.Date > tempAccountServices.StartDate ) AND
							( CommonDevices.Date <= tempAccountServices.EndDate ) AND
							( CommonDevices.ObjectId = tempAccountServices.ParentId ) AND
							( CommonDevices.ServiceId = tempAccountServices.ServiceId ) AND
							( CommonDevices.TariffId = tempAccountServices.TariffId )
						WHERE 
							( CommonDevices.ObjectId IN ( SELECT 
															  tempAccountServices.ParentId AS ParentId 
														  FROM 
															  tempAccountServices AS tempAccountServices ) ) ) AS CommonDevices ON 
						( CommonDevices.Id = tempAccountServices.Id ) AND
						( CommonDevices.Date >= tempAccountServices.StartDate ) AND
						( CommonDevices.Date < tempAccountServices.EndDate );


				CREATE TEMPORARY TABLE tempParentDevices
					SELECT 
						prevParentDevices.Id AS Id,
						prevParentDevices.ParentId AS ParentId,
						prevParentDevices.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextParentDevices.StartDate
							FROM 
								prevParentDevices AS nextParentDevices
							WHERE
								( prevParentDevices.StartDate < nextParentDevices.StartDate ) AND
								( prevParentDevices.Id = nextParentDevices.Id )
							ORDER BY
								prevParentDevices.Id,
								prevParentDevices.StartDate
							LIMIT 1 ), prevParentDevices.EndDate ) AS EndDate,
						prevParentDevices.ServiceId AS ServiceId,
						prevParentDevices.TariffId AS TariffId,
						prevParentDevices.Portion AS Portion,
						prevParentDevices.Сoefficient AS Сoefficient,
						prevParentDevices.DeviceId AS DeviceId
					FROM 
						prevParentDevices AS prevParentDevices
					ORDER BY
						prevParentDevices.Id,
						prevParentDevices.StartDate;


				CREATE TEMPORARY TABLE tempParentIndications
					SELECT 
						tempParentDevices.Id AS Id,
						tempParentDevices.ParentId AS ParentId,
						tempParentDevices.StartDate AS StartDate,
						tempParentDevices.EndDate AS EndDate,
						tempParentDevices.ServiceId AS ServiceId,
						tempParentDevices.TariffId AS TariffId,
						tempParentDevices.Portion AS Portion,
						tempParentDevices.Сoefficient AS Сoefficient,
						tempParentDevices.DeviceId AS DeviceId,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempParentDevices.StartDate ) AND
								( DeviceIndications.DeviceId = tempParentDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS PreviousIndications,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempParentDevices.EndDate ) AND
								( DeviceIndications.DeviceId = tempParentDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS CurrentIndications,
						DATEDIFF( tempParentDevices.StartDate, tempParentDevices.EndDate ) / DATEDIFF( @start_date, @end_date ) AS ParentPortion
					FROM 
						tempParentDevices AS tempParentDevices;


				DELETE 
				FROM Profit
				WHERE 
					( Period = @start_date ) AND 
					( AccountId IN ( SELECT 
										 tempPersonalAccounts.AccountId AS AccountID 
									 FROM 
										 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
					( CalculationType = @calculation_type );
										 

				INSERT INTO Profit ( Period, StartDate, EndDate, AccountID, ServiceId, TariffId, CalculationType, Portion, Сoefficient, ObjectId, ObjectSquare, ParentId, ParentIndications, TotalIndications, SharedIndications, TotalSquare, PortionSquare, Price, Summa )
					SELECT 
						@start_date AS Period,
						@start_date AS StartDate,
						@end_date AS EndDate,
						Result.AccountId AS AccountId,
						Result.ServiceId AS ServiceId,
						Result.TariffId AS TariffId,
						@calculation_type AS CalculationType,
						Result.Portion AS Portion,
						Result.Сoefficient AS Сoefficient,
						Result.ObjectId AS ObjectId,
						Result.ObjectSquare AS ObjectSquare,
						Result.ParentId AS ParentId,		
						Result.ParentIndications AS ParentIndications,
						Result.TotalIndications AS TotalIndications,
						Result.SharedIndications AS SharedIndications,
						Result.TotalSquare AS TotalSquare,
						Result.PortionSquare AS PortionSquare,
						Result.Price AS Price,
						ROUND( Result.SharedIndications * Result.PortionSquare * Result.Portion * ( 1 + Result.Сoefficient ) * Result.Price, 2 ) AS Summa
					FROM
						( SELECT 
							tempAccountServices.AccountId AS AccountId,
							tempAccountServices.ServiceId AS ServiceId,
							tempAccountServices.TariffId AS TariffId,
							tempAccountServices.Portion AS Portion,
							tempAccountServices.Сoefficient AS Сoefficient,
							tempAccountServices.ObjectId AS ObjectId,
							tempAccountServices.ObjectSquare AS ObjectSquare,
							tempAccountServices.ParentId AS ParentId,
							IFNULL( tempParentIndications.Indications, 0 ) AS ParentIndications,
							IFNULL( tempDeviceIndications.Indications, 0 ) AS TotalIndications,
							IFNULL( tempParentIndications.Indications, 0 ) - IFNULL( tempDeviceIndications.Indications, 0 ) AS SharedIndications,			
							tempAccountServices.TotalSquare AS TotalSquare,
							CASE 
								WHEN ( IFNULL( tempAccountServices.TotalSquare, 0 ) = 0 ) THEN 0
								ELSE IFNULL( tempAccountServices.ObjectSquare, 0 ) / IFNULL( tempAccountServices.TotalSquare, 1 )
							END AS PortionSquare,
							IFNULL( ( SELECT 
									Tariffs.Price
								FROM 
									Tariffs AS Tariffs
								WHERE
									( Tariffs.Date <= tempAccountServices.EndDate ) AND
									( Tariffs.CompanyId = @company_id ) AND
									( Tariffs.ServiceId = tempAccountServices.ServiceId ) AND
									( Tariffs.TariffId = tempAccountServices.TariffId )
								ORDER BY
									Tariffs.Date DESC
								LIMIT 1 ), 0 ) AS Price
						FROM
							tempAccountServices AS tempAccountServices
						LEFT JOIN 
							( SELECT
								  tempParentIndications.Id AS Id,
								  SUM( ( tempParentIndications.CurrentIndications - tempParentIndications.PreviousIndications ) * tempParentIndications.ParentPortion ) AS Indications
							  FROM 
								  tempParentIndications AS tempParentIndications
							  GROUP BY
								  tempParentIndications.Id ) AS tempParentIndications ON	
							( tempParentIndications.Id = tempAccountServices.Id )
						LEFT JOIN 
							( SELECT
								  tempDeviceIndications.ParentId AS ParentId,
								  SUM( ( tempDeviceIndications.CurrentIndications - tempDeviceIndications.PreviousIndications ) * tempDeviceIndications.DevicePortion ) AS Indications
							  FROM 
								  tempDeviceIndications AS tempDeviceIndications
							  GROUP BY
								  tempDeviceIndications.ParentId ) AS tempDeviceIndications ON	
							( tempDeviceIndications.ParentId = tempAccountServices.ParentId ) ) AS Result;


				UPDATE 
					DeviceIndications 
				SET 
					Fixed = 1
				WHERE 
					( DeviceId IN ( SELECT 
										tempParentIndications.DeviceId AS DeviceId 
									FROM 
										tempParentIndications AS tempParentIndications ) ) AND
					( Date <= @end_date ) AND
					( Fixed = 0 );";
	
	//echo '4. Пропорционально площадей объектов недвижимости<br>';
	$mysqli = mysqli_connect( $host, $user, $password, $database ) or die( "Ошибка: " . mysqli_error( $mysqli ) );
	
	if ( mysqli_multi_query( $mysqli, $query ) or die( "Ошибка: " . mysqli_error( $mysqli ) ) ) { 
		do {
			if ( $result = mysqli_store_result( $mysqli ) ) {
				mysqli_free_result( $result );
			}
		} while ( mysqli_more_results( $mysqli ) && mysqli_next_result( $mysqli ) );
	}
	
	mysqli_close( $mysqli );

	// Пропорционально показаний индивидуальных и общедомовых приборов учета
	$query = "	SET @company_id       := " . $CompanyId . ";
				SET @calculation_type := 5;
				SET @start_date       := '" . $Period . "';
				SET @start_date       := LAST_DAY( @start_date ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND;
				SET @end_date         := DATE_ADD( @start_date, INTERVAL 1 MONTH );
				SET @id               := 0;


				CREATE TEMPORARY TABLE tempPersonalAccounts
					SELECT
						PersonalAccounts.AccountId AS AccountId,
						Objects.ObjectId AS ObjectId,
						Objects.ParentId AS ParentId
					FROM Objects AS Objects
					LEFT JOIN PersonalAccounts AS PersonalAccounts ON 
						( PersonalAccounts.ObjectId = Objects.ObjectId )
					WHERE
						( PersonalAccounts.CompanyId = @company_id ) AND
						( IFNULL( PersonalAccounts.Using, 1 ) = 1 );


				CREATE TEMPORARY TABLE prevAccountServices
					SELECT 
						@start_date AS Date,
						AccountServicesMax.AccountId AS AccountId,
						AccountServicesMax.ServiceId AS ServiceId,
						AccountServicesMax.TariffId AS TariffId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient,
						tempPersonalAccounts.ObjectId AS ObjectId,
						tempPersonalAccounts.ParentId AS ParentId
					FROM ( 
						SELECT 
							MAX( AccountServices.Date ) AS DateMax,
							AccountServices.AccountId AS AccountId,
							AccountServices.ServiceId AS ServiceId,
							AccountServices.TariffId AS TariffId
						FROM 
							AccountServices AS AccountServices
						WHERE 
							( AccountServices.Date < @start_date ) AND 
							( AccountServices.AccountId IN ( SELECT 
																 tempPersonalAccounts.AccountId AS AccountID 
															 FROM 
																 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
							( AccountServices.CalculationType = @calculation_type )
						GROUP BY
							AccountServices.AccountId,
							AccountServices.ServiceId,
							AccountServices.TariffId,
							AccountServices.CalculationType ) AS AccountServicesMax
					LEFT JOIN AccountServices AS AccountServices ON 
						( AccountServices.Date = AccountServicesMax.DateMax ) AND
						( AccountServices.AccountId = AccountServicesMax.AccountId ) AND
						( AccountServices.ServiceId = AccountServicesMax.ServiceId ) AND
						( AccountServices.TariffId = AccountServicesMax.TariffId ) AND
						( AccountServices.CalculationType = @calculation_type )
					LEFT JOIN tempPersonalAccounts AS tempPersonalAccounts ON 
						( tempPersonalAccounts.AccountId = AccountServicesMax.AccountId )

					UNION ALL

					SELECT 
						AccountServices.Date AS Date,
						AccountServices.AccountId AS AccountId,
						AccountServices.ServiceId AS ServiceId,
						AccountServices.TariffId AS TariffId,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Portion, 1 )
							ELSE 0
						END AS Portion,
						CASE 
							WHEN ( IFNULL( AccountServices.Using, 0 ) = 1 ) THEN IFNULL( AccountServices.Сoefficient, 0 )
							ELSE 0
						END AS Сoefficient,
						tempPersonalAccounts.ObjectId AS ObjectId,
						tempPersonalAccounts.ParentId AS ParentId
					FROM 
						AccountServices AS AccountServices
					LEFT JOIN tempPersonalAccounts AS tempPersonalAccounts ON 
						( tempPersonalAccounts.AccountId = AccountServices.AccountId )
					WHERE 
						( AccountServices.Date >= @start_date ) AND 
						( AccountServices.Date < @end_date ) AND 
						( AccountServices.AccountId IN ( SELECT 
															 tempPersonalAccounts.AccountId AS AccountID 
														 FROM 
															 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
						( AccountServices.CalculationType = @calculation_type );


				CREATE TEMPORARY TABLE tempAccountServices
					SELECT 
						@id := @id + 1 AS Id,
						prevAccountServices.Date AS StartDate,
						IFNULL( ( SELECT 
								nextAccountServices.Date
							FROM 
								prevAccountServices AS nextAccountServices
							WHERE
								( prevAccountServices.Date < nextAccountServices.Date ) AND
								( prevAccountServices.AccountId = nextAccountServices.AccountId ) AND
								( prevAccountServices.ServiceId = nextAccountServices.ServiceId ) AND
								( prevAccountServices.TariffId = nextAccountServices.TariffId )
							ORDER BY
								prevAccountServices.AccountId,
								prevAccountServices.ServiceId,
								prevAccountServices.TariffId,
								prevAccountServices.Date
							LIMIT 1 ), @end_date ) AS EndDate,
						prevAccountServices.AccountId AS AccountId,
						prevAccountServices.ServiceId AS ServiceId,
						prevAccountServices.TariffId AS TariffId,
						prevAccountServices.Portion AS Portion,
						prevAccountServices.Сoefficient AS Сoefficient,
						prevAccountServices.ObjectId AS ObjectId,
						prevAccountServices.ParentId AS ParentId
					FROM 
						prevAccountServices AS prevAccountServices
					ORDER BY
						prevAccountServices.AccountId,
						prevAccountServices.ServiceId,
						prevAccountServices.TariffId,
						prevAccountServices.Date;


				CREATE TEMPORARY TABLE prevAccountDevices
					SELECT 
						tempAccountServices.Id AS Id,
						tempAccountServices.ObjectId AS ObjectId,
						tempAccountServices.ParentId AS ParentId,
						IFNULL( AccountDevices.Date, tempAccountServices.StartDate ) AS StartDate,
						tempAccountServices.EndDate AS EndDate,
						tempAccountServices.AccountId AS AccountId,
						tempAccountServices.ServiceId AS ServiceId,
						tempAccountServices.TariffId AS TariffId,
						tempAccountServices.Portion AS Portion,
						tempAccountServices.Сoefficient AS Сoefficient,
						AccountDevices.DeviceId AS DeviceId
					FROM 
						tempAccountServices AS tempAccountServices
					LEFT JOIN 
						( SELECT 
							AccountDevicesMax.Id AS Id,
							AccountDevicesMax.StartDate AS Date,
							AccountDevicesMax.AccountId AS AccountId,
							AccountDevicesMax.ServiceId AS ServiceId,
							AccountDevicesMax.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( AccountDevices.Using, 0 ) = 1 ) THEN AccountDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							( SELECT 
								tempAccountServices.Id AS Id,
								tempAccountServices.StartDate AS StartDate,
								AccountDevices.AccountId AS AccountId,
								AccountDevices.ServiceId AS ServiceId,
								AccountDevices.TariffId AS TariffId,
								MAX( AccountDevices.Date ) AS DateMax
							FROM 
								tempAccountServices AS tempAccountServices
							LEFT JOIN AccountDevices AS AccountDevices ON 
								( AccountDevices.Date <= tempAccountServices.StartDate ) AND
								( AccountDevices.AccountId = tempAccountServices.AccountId ) AND
								( AccountDevices.ServiceId = tempAccountServices.ServiceId ) AND
								( AccountDevices.TariffId = tempAccountServices.TariffId )
							WHERE 
								( AccountDevices.AccountId IN ( SELECT 
																	tempAccountServices.AccountId AS AccountID 
																FROM 
																	tempAccountServices AS tempAccountServices ) )
							GROUP BY
								tempAccountServices.Id,
								tempAccountServices.StartDate,
								AccountDevices.AccountId,
								AccountDevices.ServiceId,
								AccountDevices.TariffId ) AS AccountDevicesMax
						LEFT JOIN AccountDevices AS AccountDevices ON 
							( AccountDevices.AccountId = AccountDevicesMax.AccountId ) AND
							( AccountDevices.ServiceId = AccountDevicesMax.ServiceId ) AND
							( AccountDevices.TariffId = AccountDevicesMax.TariffId ) AND
							( AccountDevices.Date = AccountDevicesMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountServices.Id AS Id,
							AccountDevices.Date AS Date,
							AccountDevices.AccountId AS AccountId,
							AccountDevices.ServiceId AS ServiceId,
							AccountDevices.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( AccountDevices.Using, 0 ) = 1 ) THEN AccountDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							tempAccountServices AS tempAccountServices
						LEFT JOIN AccountDevices AS AccountDevices ON 
							( AccountDevices.Date > tempAccountServices.StartDate ) AND
							( AccountDevices.Date <= tempAccountServices.EndDate ) AND
							( AccountDevices.AccountId = tempAccountServices.AccountId ) AND
							( AccountDevices.ServiceId = tempAccountServices.ServiceId ) AND
							( AccountDevices.TariffId = tempAccountServices.TariffId )
						WHERE 
							( AccountDevices.AccountId IN ( SELECT 
																tempAccountServices.AccountId AS AccountID 
															FROM 
																tempAccountServices AS tempAccountServices ) ) ) AS AccountDevices ON 
						( AccountDevices.Id = tempAccountServices.Id ) AND
						( AccountDevices.Date >= tempAccountServices.StartDate ) AND
						( AccountDevices.Date < tempAccountServices.EndDate );


				CREATE TEMPORARY TABLE tempAccountDevices
					SELECT 
						prevAccountDevices.Id AS Id,
						prevAccountDevices.ObjectId AS ObjectId,
						prevAccountDevices.ParentId AS ParentId,
						prevAccountDevices.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextAccountDevices.StartDate
							FROM 
								prevAccountDevices AS nextAccountDevices
							WHERE
								( prevAccountDevices.StartDate < nextAccountDevices.StartDate ) AND
								( prevAccountDevices.Id = nextAccountDevices.Id )
							ORDER BY
								prevAccountDevices.Id,
								prevAccountDevices.StartDate
							LIMIT 1 ), prevAccountDevices.EndDate ) AS EndDate,
						prevAccountDevices.AccountId AS AccountId,
						prevAccountDevices.ServiceId AS ServiceId,
						prevAccountDevices.TariffId AS TariffId,
						prevAccountDevices.Portion AS Portion,
						prevAccountDevices.Сoefficient AS Сoefficient,
						prevAccountDevices.DeviceId AS DeviceId
					FROM 
						prevAccountDevices AS prevAccountDevices
					ORDER BY
						prevAccountDevices.Id,
						prevAccountDevices.StartDate;


				CREATE TEMPORARY TABLE tempDeviceIndications
					SELECT 
						tempAccountDevices.Id AS Id,
						tempAccountDevices.ObjectId AS ObjectId,
						tempAccountDevices.ParentId AS ParentId,
						tempAccountDevices.StartDate AS StartDate,
						tempAccountDevices.EndDate AS EndDate,
						tempAccountDevices.AccountId AS AccountId,
						tempAccountDevices.ServiceId AS ServiceId,
						tempAccountDevices.TariffId AS TariffId,
						tempAccountDevices.Portion AS Portion,
						tempAccountDevices.Сoefficient AS Сoefficient,
						tempAccountDevices.DeviceId AS DeviceId,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempAccountDevices.StartDate ) AND
								( DeviceIndications.DeviceId = tempAccountDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS PreviousIndications,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempAccountDevices.EndDate ) AND
								( DeviceIndications.DeviceId = tempAccountDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS CurrentIndications,
						DATEDIFF( tempAccountDevices.StartDate, tempAccountDevices.EndDate ) / DATEDIFF( @start_date, @end_date ) AS DevicePortion
					FROM 
						tempAccountDevices AS tempAccountDevices;


				CREATE TEMPORARY TABLE prevParentDevices
					SELECT 
						tempAccountServices.Id AS Id,
						tempAccountServices.ObjectId AS ObjectId,
						tempAccountServices.ParentId AS ParentId,
						IFNULL( CommonDevices.Date, tempAccountServices.StartDate ) AS StartDate,
						tempAccountServices.EndDate AS EndDate,
						tempAccountServices.ServiceId AS ServiceId,
						tempAccountServices.TariffId AS TariffId,
						tempAccountServices.Portion AS Portion,
						tempAccountServices.Сoefficient AS Сoefficient,
						CommonDevices.DeviceId AS DeviceId
					FROM 
						tempAccountServices AS tempAccountServices
					LEFT JOIN 
						( SELECT 
							CommonDevicesMax.Id AS Id,
							CommonDevicesMax.StartDate AS Date,
							CommonDevicesMax.ParentId AS ParentId,
							CommonDevicesMax.ServiceId AS ServiceId,
							CommonDevicesMax.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( CommonDevices.Using, 0 ) = 1 ) THEN CommonDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							( SELECT 
								tempAccountServices.Id AS Id,
								tempAccountServices.StartDate AS StartDate,
								CommonDevices.ObjectId AS ParentId,
								CommonDevices.ServiceId AS ServiceId,
								CommonDevices.TariffId AS TariffId,
								MAX( CommonDevices.Date ) AS DateMax
							FROM 
								tempAccountServices AS tempAccountServices
							LEFT JOIN CommonDevices AS CommonDevices ON 
								( CommonDevices.Date <= tempAccountServices.StartDate ) AND
								( CommonDevices.ObjectId = tempAccountServices.ParentId ) AND
								( CommonDevices.ServiceId = tempAccountServices.ServiceId ) AND
								( CommonDevices.TariffId = tempAccountServices.TariffId )
							WHERE 
								( CommonDevices.ObjectId IN ( SELECT 
																  tempAccountServices.ParentId AS ParentId 
															  FROM 
																  tempAccountServices AS tempAccountServices ) )
							GROUP BY
								tempAccountServices.Id,
								tempAccountServices.StartDate,
								CommonDevices.ObjectId,
								CommonDevices.ServiceId,
								CommonDevices.TariffId ) AS CommonDevicesMax
						LEFT JOIN CommonDevices AS CommonDevices ON 
							( CommonDevices.ObjectId = CommonDevicesMax.ParentId ) AND
							( CommonDevices.ServiceId = CommonDevicesMax.ServiceId ) AND
							( CommonDevices.TariffId = CommonDevicesMax.TariffId ) AND
							( CommonDevices.Date = CommonDevicesMax.DateMax )

						UNION ALL
						
						SELECT 
							tempAccountServices.Id AS Id,
							CommonDevices.Date AS Date,
							CommonDevices.ObjectId AS ParentId,
							CommonDevices.ServiceId AS ServiceId,
							CommonDevices.TariffId AS TariffId,
							CASE 
								WHEN ( IFNULL( CommonDevices.Using, 0 ) = 1 ) THEN CommonDevices.DeviceId
								ELSE NULL
							END AS DeviceId
						FROM 
							tempAccountServices AS tempAccountServices
						LEFT JOIN CommonDevices AS CommonDevices ON 
							( CommonDevices.Date > tempAccountServices.StartDate ) AND
							( CommonDevices.Date <= tempAccountServices.EndDate ) AND
							( CommonDevices.ObjectId = tempAccountServices.ParentId ) AND
							( CommonDevices.ServiceId = tempAccountServices.ServiceId ) AND
							( CommonDevices.TariffId = tempAccountServices.TariffId )
						WHERE 
							( CommonDevices.ObjectId IN ( SELECT 
															  tempAccountServices.ParentId AS ParentId 
														  FROM 
															  tempAccountServices AS tempAccountServices ) ) ) AS CommonDevices ON 
						( CommonDevices.Id = tempAccountServices.Id ) AND
						( CommonDevices.Date >= tempAccountServices.StartDate ) AND
						( CommonDevices.Date < tempAccountServices.EndDate );


				CREATE TEMPORARY TABLE tempParentDevices
					SELECT 
						prevParentDevices.Id AS Id,
						prevParentDevices.ParentId AS ParentId,
						prevParentDevices.StartDate AS StartDate,
						IFNULL( ( SELECT 
								nextParentDevices.StartDate
							FROM 
								prevParentDevices AS nextParentDevices
							WHERE
								( prevParentDevices.StartDate < nextParentDevices.StartDate ) AND
								( prevParentDevices.Id = nextParentDevices.Id )
							ORDER BY
								prevParentDevices.Id,
								prevParentDevices.StartDate
							LIMIT 1 ), prevParentDevices.EndDate ) AS EndDate,
						prevParentDevices.ServiceId AS ServiceId,
						prevParentDevices.TariffId AS TariffId,
						prevParentDevices.Portion AS Portion,
						prevParentDevices.Сoefficient AS Сoefficient,
						prevParentDevices.DeviceId AS DeviceId
					FROM 
						prevParentDevices AS prevParentDevices
					ORDER BY
						prevParentDevices.Id,
						prevParentDevices.StartDate;


				CREATE TEMPORARY TABLE tempParentIndications
					SELECT 
						tempParentDevices.Id AS Id,
						tempParentDevices.ParentId AS ParentId,
						tempParentDevices.StartDate AS StartDate,
						tempParentDevices.EndDate AS EndDate,
						tempParentDevices.ServiceId AS ServiceId,
						tempParentDevices.TariffId AS TariffId,
						tempParentDevices.Portion AS Portion,
						tempParentDevices.Сoefficient AS Сoefficient,
						tempParentDevices.DeviceId AS DeviceId,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempParentDevices.StartDate ) AND
								( DeviceIndications.DeviceId = tempParentDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS PreviousIndications,
						IFNULL( ( SELECT 
								DeviceIndications.Indications
							FROM 
								DeviceIndications AS DeviceIndications
							WHERE
								( DeviceIndications.Date <= tempParentDevices.EndDate ) AND
								( DeviceIndications.DeviceId = tempParentDevices.DeviceId )
							ORDER BY
								DeviceIndications.Date DESC
							LIMIT 1 ), 0 ) AS CurrentIndications,
						DATEDIFF( tempParentDevices.StartDate, tempParentDevices.EndDate ) / DATEDIFF( @start_date, @end_date ) AS ParentPortion
					FROM 
						tempParentDevices AS tempParentDevices;


				DELETE 
				FROM Profit
				WHERE 
					( Period = @start_date ) AND 
					( AccountId IN ( SELECT 
										 tempPersonalAccounts.AccountId AS AccountID 
									 FROM 
										 tempPersonalAccounts AS tempPersonalAccounts ) ) AND
					( CalculationType = @calculation_type );
										 

				INSERT INTO Profit ( Period, StartDate, EndDate, AccountID, ServiceId, TariffId, CalculationType, Portion, Сoefficient, ObjectId, ParentId, ParentIndications, TotalIndications, SharedIndications, IndividualIndications, PortionIndications, Price, Summa )
					SELECT 
						@start_date AS Period,
						@start_date AS StartDate,
						@end_date AS EndDate,
						Result.AccountId AS AccountId,
						Result.ServiceId AS ServiceId,
						Result.TariffId AS TariffId,
						@calculation_type AS CalculationType,
						Result.Portion AS Portion,
						Result.Сoefficient AS Сoefficient,
						Result.ObjectId AS ObjectId,
						Result.ParentId AS ParentId,		
						Result.ParentIndications AS ParentIndications,
						Result.TotalIndications AS TotalIndications,
						Result.SharedIndications AS SharedIndications,
						Result.IndividualIndications AS IndividualIndications,
						Result.PortionIndications AS PortionIndications,
						Result.Price AS Price,
						ROUND( Result.SharedIndications * Result.PortionIndications * Result.Portion * ( 1 + Result.Сoefficient ) * Result.Price, 2 ) AS Summa
					FROM
						( SELECT 
							tempAccountServices.AccountId AS AccountId,
							tempAccountServices.ServiceId AS ServiceId,
							tempAccountServices.TariffId AS TariffId,
							tempAccountServices.Portion AS Portion,
							tempAccountServices.Сoefficient AS Сoefficient,
							tempAccountServices.ObjectId AS ObjectId,
							tempAccountServices.ParentId AS ParentId,
							IFNULL( tempParentIndications.Indications, 0 ) AS ParentIndications,
							IFNULL( tempDeviceIndications.Indications, 0 ) AS TotalIndications,
							IFNULL( tempParentIndications.Indications, 0 ) - IFNULL( tempDeviceIndications.Indications, 0 ) AS SharedIndications,			
							IFNULL( indDeviceIndications.Indications, 0 ) AS IndividualIndications,
							CASE 
								WHEN ( IFNULL( tempDeviceIndications.Indications, 0 ) = 0 ) THEN 0
								ELSE IFNULL( indDeviceIndications.Indications, 0 ) / IFNULL( tempDeviceIndications.Indications, 1 )
							END AS PortionIndications,
							IFNULL( ( SELECT 
									Tariffs.Price
								FROM 
									Tariffs AS Tariffs
								WHERE
									( Tariffs.Date <= tempAccountServices.EndDate ) AND
									( Tariffs.CompanyId = @company_id ) AND
									( Tariffs.ServiceId = tempAccountServices.ServiceId ) AND
									( Tariffs.TariffId = tempAccountServices.TariffId )
								ORDER BY
									Tariffs.Date DESC
								LIMIT 1 ), 0 ) AS Price
						FROM
							tempAccountServices AS tempAccountServices
						LEFT JOIN 
							( SELECT
								  tempParentIndications.Id AS Id,
								  SUM( ( tempParentIndications.CurrentIndications - tempParentIndications.PreviousIndications ) * tempParentIndications.ParentPortion ) AS Indications
							  FROM 
								  tempParentIndications AS tempParentIndications
							  GROUP BY
								  tempParentIndications.Id ) AS tempParentIndications ON	
							( tempParentIndications.Id = tempAccountServices.Id )
						LEFT JOIN 
							( SELECT
								  tempDeviceIndications.ObjectId AS ObjectId,
								  SUM( ( tempDeviceIndications.CurrentIndications - tempDeviceIndications.PreviousIndications ) * tempDeviceIndications.DevicePortion ) AS Indications
							  FROM 
								  tempDeviceIndications AS tempDeviceIndications
							  GROUP BY
								  tempDeviceIndications.ObjectId) AS indDeviceIndications ON	
							( indDeviceIndications.ObjectId = tempAccountServices.ObjectId )
						LEFT JOIN 
							( SELECT
								  tempDeviceIndications.ParentId AS ParentId,
								  SUM( ( tempDeviceIndications.CurrentIndications - tempDeviceIndications.PreviousIndications ) * tempDeviceIndications.DevicePortion ) AS Indications
							  FROM 
								  tempDeviceIndications AS tempDeviceIndications
							  GROUP BY
								  tempDeviceIndications.ParentId ) AS tempDeviceIndications ON	
							( tempDeviceIndications.ParentId = tempAccountServices.ParentId ) ) AS Result;


				UPDATE 
					DeviceIndications 
				SET 
					Fixed = 1
				WHERE 
					( DeviceId IN ( SELECT 
										tempParentIndications.DeviceId AS DeviceId 
									FROM 
										tempParentIndications AS tempParentIndications ) ) AND
					( Date <= @end_date ) AND
					( Fixed = 0 );";
					
	//echo '5. Пропорционально показаний индивидуальных и общедомовых приборов учета<br>';
	$mysqli = mysqli_connect( $host, $user, $password, $database ) or die( "Ошибка: " . mysqli_error( $mysqli ) );
	
	if ( mysqli_multi_query( $mysqli, $query ) or die( "Ошибка: " . mysqli_error( $mysqli ) ) ) { 
		do {
			if ( $result = mysqli_store_result( $mysqli ) ) {
				mysqli_free_result( $result );
			}
		} while ( mysqli_more_results( $mysqli ) && mysqli_next_result( $mysqli ) );
	}

	mysqli_close( $mysqli );

	// Задолженность
	$query = "	SET @company_id := " . $CompanyId . ";
				SET @start_date := '" . $Period . "';
				SET @start_date := LAST_DAY( @start_date ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND;


				CREATE TEMPORARY TABLE tempPersonalAccounts
					SELECT
						PersonalAccounts.AccountId AS AccountId
					FROM 
						PersonalAccounts AS PersonalAccounts
					WHERE
						( PersonalAccounts.CompanyId = @company_id ) AND
						( IFNULL( PersonalAccounts.Using, 0 ) = 1 );


				DELETE 
				FROM Debt
				WHERE 
					( Period >= @start_date ) AND 
					( AccountId IN ( SELECT 
										 tempPersonalAccounts.AccountId AS AccountID 
									 FROM 
										 tempPersonalAccounts AS tempPersonalAccounts ) );
										 
										 
				INSERT INTO Debt ( Period, AccountID, Summa )
					SELECT	
						Result.Period AS Period,
						Result.AccountId AS AccountId,
						SUM( Result.Summa ) AS Summa
					FROM
						( SELECT	
							Profit.Period AS Period,
							Profit.AccountId AS AccountId,
							Profit.Summa AS Summa
						FROM
							Profit AS Profit
						WHERE
							( Profit.Period >= @start_date ) AND 
							( Profit.AccountId IN ( SELECT 
														tempPersonalAccounts.AccountId AS AccountID 
													FROM 
														tempPersonalAccounts AS tempPersonalAccounts ) )
												 
						UNION ALL

						SELECT	
							LAST_DAY( Payment.Date ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND AS Period,
							Payment.AccountId AS AccountId,
							-Payment.Summa AS Summa
						FROM
							Payment AS Payment
						WHERE
							( Payment.Date >= @start_date ) AND 
							( Payment.AccountId IN ( SELECT 
														 tempPersonalAccounts.AccountId AS AccountID 
													 FROM 
														 tempPersonalAccounts AS tempPersonalAccounts ) ) ) AS Result
					GROUP BY
						Result.Period,
						Result.AccountId;";
	
	//echo '6. Задолженность абонентов<br>';
	$mysqli = mysqli_connect( $host, $user, $password, $database ) or die( "Ошибка: " . mysqli_error( $mysqli ) );
	
	if ( mysqli_multi_query( $mysqli, $query ) or die( "Ошибка: " . mysqli_error( $mysqli ) ) ) { 
		do {
			if ( $result = mysqli_store_result( $mysqli ) ) {
				mysqli_free_result( $result );
			}
		} while ( mysqli_more_results( $mysqli ) && mysqli_next_result( $mysqli ) );
	}
	
	mysqli_close( $mysqli );
	//echo 'Done';

	header( "Location: ../index.php?table=Profit" );
?>