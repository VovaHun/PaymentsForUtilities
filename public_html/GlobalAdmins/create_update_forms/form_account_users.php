<?php
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_header.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_admin_header.php';
?>

<body>
	<?php
		$user_id    = $_GET[ 'UserId' ];
		$account_id = $_GET[ 'AccountId' ];
		$fn         = $names_ar[ 'AccountUsers' ];
		$row        = array( 'UserId'    => -1, 
		                     'AccountId' => -1, 
							 'Active'    => 0,
							 'Access'    => 0 );
		
		if ( ( $user_id != -1 ) && ( $account_id != -1 ) ) {
			mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
			$query  = "SELECT 
						   AccountUsers.UserId AS UserId,
						   AccountUsers.AccountId AS AccountId,
						   AccountUsers.Active AS Active,
						   AccountUsers.Access AS Access
					   FROM AccountUsers AS AccountUsers 
					   WHERE ( AccountUsers.UserId = " . $user_id . " ) AND
							 ( AccountUsers.AccountId = " . $account_id . " )
					   LIMIT 1";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
			
			if ( !$row = mysqli_fetch_array( $result ) ) {
				$row = array( 'UserId'    => -1, 
							  'AccountId' => -1, 
							  'Active'    => 0,
							  'Access'    => 0 );
			}
		}
	?>
	
    <div class="main">
        <div class="container content">
            <div class="main__left">
				<div class="container">
					<div class="content__title">
						<h5>Пользователь - Абонент</h5>
					</div>
					<form class="" method="post" action="../create_update_query/update_account_users.php">
						<div class="form_wrapper">
							<?php
								echo "<input type='hidden' name='prevUserId' id='prevUserId' value='" . $user_id . "'>";
								echo "<input type='hidden' name='prevAccountId' id='prevAccountId' value='" . $account_id . "'>";
								echo "<table>";
									echo "<tr>";
										echo "<td><label for='UserId'>" . $fn[ 0 ] . ":</label></td>";
										echo "<td><select class='select_db' name='UserId' id='UserId' required>";
											$query1 = "SELECT 
														   Users.UserId AS UserId,
														   Users.Login AS Login,
														   Users.Name AS Name
													   FROM Users AS Users
													   ORDER BY Users.Name,
																Users.Login";
											$result1 = mysqli_query( $link, $query1 ) or die( "Ошибка: " . mysqli_error( $link ) ); 
											
											while ( $row1 = mysqli_fetch_array( $result1 ) ) {
												echo "<option " . ( ( $row[ 'UserId' ] == $row1[ 'UserId' ] ) ? "selected " : "" ) . " value='" . $row1[ 'UserId' ] . "'>" . $row1[ 'Name' ] . ( empty( $row1[ 'Name' ] ) ? $row1[ 'Login' ] : ( empty( $row1[ 'Login' ] ) ? "" : " (" . $row1[ 'Login' ] . ")" ) ) . "</option>";
											}
										echo "</select></td>";
									echo "</tr>";								
									echo "<tr>";
										echo "<td><label for='AccountId'>" . $fn[ 1 ] . ":</label></td>";
										echo "<td><select class='select_db' name='AccountId' id='AccountId' required>";
											$query1 = "SELECT 
														   PersonalAccounts.AccountId AS AccountId,
														   PersonalAccounts.Name AS Name
													   FROM PersonalAccounts AS PersonalAccounts
													   ORDER BY PersonalAccounts.Name";
											$result1 = mysqli_query( $link, $query1 ) or die( "Ошибка: " . mysqli_error( $link ) ); 
											
											while ( $row1 = mysqli_fetch_array( $result1 ) ) {
												echo "<option " . ( ( $row[ 'AccountId' ] == $row1[ 'AccountId' ] ) ? "selected " : "" ) . " value='" . $row1[ 'AccountId' ] . "'>" . $row1[ 'Name' ] . "</option>";
											}
										echo "</select></td>";
									echo "</tr>";								
									echo "<tr>";
										echo "<td><label for='Active'>" . $fn[ 2 ] . ":</label></td>";
										echo "<td><input type=checkbox " . ( ( $row[ 'Active' ] == 1 ) ? "checked " : "" ) . " name='Active' id='Active'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Access'>" . $fn[ 3 ] . ":</label></td>";
										echo "<td><input type=checkbox " . ( ( $row[ 'Access' ] == 1 ) ? "checked " : "" ) . " name='Access' id='Access'></td>";
									echo "</tr>";
								echo "</table>";
							?>
						</div>
						<div>
							<table width='100%'>
								<tr>
									<td>
										<input type="submit" class="confirm" value="Сохранить">
										<a href="../index.php?table=AccountUsers">Отменить</a>
									</td>
								</tr>
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