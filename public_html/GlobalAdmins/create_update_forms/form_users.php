<?php
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_header.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_admin_header.php';
?>

<body>
	<?php
		$id  = $_GET[ 'UserId' ];
		$fn  = $names_ar[ 'Users' ];
		$row = array( 'UserId'                => -1, 
					  'Login'                 => '',
					  'Password'              => '',
					  'Name'                  => '',
					  'Gender'                => '',
					  'Email'                 => '',
					  'EmailNotifications'    => 0,
					  'Phone'                 => '',
					  'PhoneNotifications'    => 0,
					  'AppealType'            => '',
					  'Appeal'                => '',
					  'Comment'               => '',
					  'ConsentOnPersonalData' => 0,
					  'BotId'                 => '',
					  'SocialId'              => '',
					  'SocialNotifications'   => 0,
					  'RegistrationDate'      => date( 'Y-m-d H:i:s' ) );
		
		if ( $id != -1 ) {
			mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
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
						   Users.SocialId AS SocialId,
						   Users.SocialNotifications AS SocialNotifications,
					       Users.RegistrationDate AS RegistrationDate
					   FROM Users AS Users 
					   WHERE ( Users.UserId = " . $id . " )
					   LIMIT 1";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
			
			if ( !$row = mysqli_fetch_array( $result ) ) {
				$row = array( 'UserId'                => -1, 
							  'Login'                 => '',
							  'Password'              => '',
							  'Name'                  => '',
							  'Gender'                => '',
							  'Email'                 => '',
							  'EmailNotifications'    => 0,
							  'Phone'                 => '',
							  'PhoneNotifications'    => 0,
							  'AppealType'            => '',
							  'Appeal'                => '',
							  'Comment'               => '',
							  'ConsentOnPersonalData' => 0,
							  'BotId'                 => '',
							  'SocialId'              => '',
							  'SocialNotifications'   => 0,
							  'RegistrationDate'      => date( 'Y-m-d H:i:s' ) );
			}
		}		
		
		$fullname    = explode( ' ', $row[ 'Name' ], 3 );
		$first_name  = ( ( count( $fullname ) >= 2 ) ? $fullname[ 1 ] : '' );
		$middle_name = ( ( count( $fullname ) >= 3 ) ? $fullname[ 2 ] : '' );
		$last_name   = ( ( count( $fullname ) >= 1 ) ? $fullname[ 0 ] : '' );
	?>
	
    <div class="main">
        <div class="container content">
            <div class="main__left">
				<div class="container">
					<div class="content__title">
						<h5>Пользователь</h5>
					</div>
					<form class="" method="post" action="../create_update_query/update_users.php">
						<div class="form_wrapper">
							<?php
								echo "<input type='hidden' name='UserId' id='UserId' value='" . $id . "'>";
								echo "<table>";
									echo "<tr>";
										echo "<td><label for='Login'>" . $fn[ 1 ] . ":</label></td>";
										echo "<td><input type=text name='Login' id='Login' value='" . $row[ 'Login' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Password'>" . $fn[ 2 ] . ":</label></td>";
										echo "<td><input type=password name='Password' id='Password' value='" . $row[ 'Password' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Name'>" . $fn[ 3 ] . ":</label></td>";
										echo "<td><input type=text name='Name' id='Name' value='" . $row[ 'Name' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Gender'>" . $fn[ 4 ] . ":</label></td>";
										echo "<td><select name='Gender' id='Gender'>";
											echo "<option value='NULL'></option>";
											echo "<option " . ( ( $row[ 'Gender' ] == 1 ) ? "selected " : "" ) . " value='1'>Мужской</option>";
											echo "<option " . ( ( $row[ 'Gender' ] == 2 ) ? "selected " : "" ) . " value='2'>Женский</option>";
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Email'>" . $fn[ 5 ] . ":</label></td>";
										echo "<td><input type=email name='Email' id='Email' value='" . $row[ 'Email' ] . "'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='EmailNotifications'>" . $fn[ 6 ] . ":</label></td>";
										echo "<td><input type=checkbox " . ( ( $row[ 'EmailNotifications' ] == 1 ) ? "checked " : "" ) . " name='EmailNotifications' id='EmailNotifications'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Phone'>" . $fn[ 7 ] . ":</label></td>";
										echo "<td><input type=text name='Phone' id='Phone' value='" . $row[ 'Phone' ] . "'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='PhoneNotifications'>" . $fn[ 8 ] . ":</label></td>";
										echo "<td><input type=checkbox " . ( ( $row[ 'PhoneNotifications' ] == 1 ) ? "checked " : "" ) . " name='PhoneNotifications' id='PhoneNotifications'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='AppealType'>" . $fn[ 10 ] . ":</label></td>";
										echo "<td><select name='AppealType' id='AppealType'>";
											echo "<option value='NULL'></option>";
											if ( !empty( $first_name ) && !empty( $last_name ) ) {
												echo "<option " . ( ( $row[ 'AppealType' ] == 1 ) ? "selected " : "" ) . " value='1'>" . $first_name . " " . $last_name . "</option>";
											}
											if ( !empty( $first_name ) && !empty( $middle_name ) ) {
												echo "<option " . ( ( $row[ 'AppealType' ] == 2 ) ? "selected " : "" ) . " value='2'>" . $first_name . " " . $middle_name . "</option>";
											}
											if ( !empty( $last_name ) ) {
												echo "<option " . ( ( $row[ 'AppealType' ] == 3 ) ? "selected " : "" ) . " value='3'>" . "г-н (г-жа) " . $last_name . "</option>";
											}
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='BotId'>" . $fn[ 13 ] . ":</label></td>";
										echo "<td><select class='select_db' name='BotId' id='BotId'>";
											echo "<option value='NULL'>&nbsp;</option>";
											$query1 = "SELECT 
														   Bots.BotId AS BotId,
														   Bots.BotType AS BotType,
														   Bots.Name AS Name
													   FROM Bots AS Bots
													   ORDER BY Bots.BotType,
																Bots.Name";
											$result1 = mysqli_query( $link, $query1 ) or die( "Ошибка: " . mysqli_error( $link ) ); 
											
											while ( $row1 = mysqli_fetch_array( $result1 ) ) {
												echo "<option " . ( ( $row[ 'BotId' ] == $row1[ 'BotId' ] ) ? "selected " : "" ) . " value='" . $row1[ 'BotId' ] . "'>" . ( ( $row1[ 'BotType' ] == 1 ) ? "Telegram, " : ( ( $row1[ 'BotType' ] == 2 ) ? "VK, " : ( ( $row1[ 'BotType' ] == 3 ) ? "WhatsApp, " : "" ) ) ) . $row1[ 'Name' ] . "</option>";
											}
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='SocialId'>" . $fn[ 14 ] . ":</label></td>";
										echo "<td><input type=text name='SocialId' id='SocialId' value='" . $row[ 'SocialId' ] . "'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='SocialNotifications'>" . $fn[ 15 ] . ":</label></td>";
										echo "<td><input type=checkbox " . ( ( $row[ 'SocialNotifications' ] == 1 ) ? "checked " : "" ) . "name='SocialNotifications' id='SocialNotifications'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Comment'>" . $fn[ 11 ] . ":</label></td>";
										echo "<td><input type=text name='Comment' id='Comment' value='" . $row[ 'Comment' ] . "'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='ConsentOnPersonalData'>" . $fn[ 12 ] . ":</label></td>";
										echo "<td><input type=checkbox " . ( ( $row[ 'ConsentOnPersonalData' ] == 1 ) ? "checked " : "" ) . "name='ConsentOnPersonalData' id='ConsentOnPersonalData'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='RegistrationDate'>" . $fn[ 16 ] . ":</label></td>";
										echo "<td><input type=datetime-local name='RegistrationDate' id='RegistrationDate' value='" . date( 'Y-m-d\TH:i:s', strtotime( $row[ 'RegistrationDate' ] ) ) . "' required></td>";
									echo "</tr>";
								echo "</table>";
							?>
						</div>
						<div>
							<table width='100%'>
								<tr>
									<td>
										<input type="submit" class="confirm" value="Сохранить">
										<a href="../index.php?table=Users">Отменить</a>
									</td>
								</tr>
								<?php
									if ( $id != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr><td>";
										echo "<a href='../index.php?table=AccountsQuery&UserId=" . $row[ 'UserId' ] . "' target='_blank'>Запросы на добавление лицевых счетов</a>";
										echo "&nbsp;&nbsp;&nbsp;";
										echo "<a href='../index.php?table=AccountUsers&UserId=" . $row[ 'UserId' ] . "' target='_blank'>Пользователи абонентов</a>";
										echo "</td></tr>";
									}
								?>
							</table>
						</div>
					</form>
				</div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="container"></div>
    </div>
</body>
</html>