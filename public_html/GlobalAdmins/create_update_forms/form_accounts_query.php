<?php
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_header.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_admin_header.php';
?>

<body>
	<?php
		$id  = $_GET[ 'QueryId' ];
		$fn  = $names_ar[ 'AccountsQuery' ];
		$row = array( 'QueryId'     => -1, 
		              'AccountName' => '', 
					  'UserId'      => 0, 
					  'CompanyId'   => 0, 
					  'ObjectId'    => '', 
					  'QueryDate'   => date( 'Y-m-d H:i:s' ),
					  'QueryStatus' => 0, 
					  'QueryAnswer' => '' );
		
		if ( $id != -1 ) {
			mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
			$query  = "SELECT 
						   AccountsQuery.QueryId AS QueryId,
						   AccountsQuery.AccountName AS AccountName,
						   AccountsQuery.UserId AS UserId,
						   AccountsQuery.CompanyId AS CompanyId,
						   AccountsQuery.ObjectId AS ObjectId,
						   AccountsQuery.QueryDate AS QueryDate,
						   AccountsQuery.QueryStatus AS QueryStatus,
						   AccountsQuery.QueryAnswer AS QueryAnswer
					   FROM AccountsQuery AS AccountsQuery 
					   WHERE ( AccountsQuery.QueryId = " . $id . " )
					   LIMIT 1";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
			
			if ( !$row = mysqli_fetch_array( $result ) ) {
				$row = array( 'QueryId'     => -1, 
							  'AccountName' => '', 
							  'UserId'      => 0, 
							  'CompanyId'   => 0, 
							  'ObjectId'    => '', 
							  'QueryDate'   => date( 'Y-m-d H:i:s' ),
							  'QueryStatus' => 0, 
							  'QueryAnswer' => '' );
			}
		}		
	?>
	
    <div class="main">
        <div class="container content">
            <div class="main__left">
				<div class="container">
					<div class="content__title">
						<h5>Запрос пользователя на добавление лицевого счета</h5>
					</div>
					<form class="" method="post" action="../create_update_query/update_accounts_query.php">
						<div class="form_wrapper">
							<?php
								echo "<input type='hidden' name='QueryId' id='QueryId' value='" . $id . "'>";
								echo "<table>";
									echo "<tr>";
										echo "<td><label for='AccountName'>" . $fn[ 1 ] . ":</label></td>";
										echo "<td><input type=text name='AccountName' id='AccountName' value='" . $row[ 'AccountName' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='UserId'>" . $fn[ 2 ] . ":</label></td>";
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
										echo "<td><label for='CompanyId'>" . $fn[ 3 ] . ":</label></td>";
										echo "<td><select class='select_db' name='CompanyId' id='CompanyId' required>";
											$query1 = "SELECT 
														   ManagementCompany.CompanyId AS CompanyId,
														   ManagementCompany.Name AS Name
													   FROM ManagementCompany AS ManagementCompany
													   ORDER BY ManagementCompany.Name";
											$result1 = mysqli_query( $link, $query1 ) or die( "Ошибка: " . mysqli_error( $link ) ); 
											
											while ( $row1 = mysqli_fetch_array( $result1 ) ) {
												echo "<option " . ( ( $row[ 'CompanyId' ] == $row1[ 'CompanyId' ] ) ? "selected " : "" ) . " value='" . $row1[ 'CompanyId' ] . "'>" . $row1[ 'Name' ] . "</option>";
											}
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='ObjectId'>" . $fn[ 4 ] . ":</label></td>";
										echo "<td><select class='select_db' name='ObjectId' id='ObjectId'>";
											echo "<option value='NULL'>&nbsp;</option>";
											$query1 = "SELECT 
														   Objects.ObjectId AS ObjectId,
														   Objects.Name AS Name,
														   Objects.KadastrNo AS KadastrNo
													   FROM Objects AS Objects
													   ORDER BY Objects.KadastrNo,
																Objects.Name";
											$result1 = mysqli_query( $link, $query1 ) or die( "Ошибка: " . mysqli_error( $link ) ); 
											
											while ( $row1 = mysqli_fetch_array( $result1 ) ) {
												echo "<option " . ( ( $row[ 'ObjectId' ] == $row1[ 'ObjectId' ] ) ? "selected " : "" ) . " value='" . $row1[ 'ObjectId' ] . "'>" . $row1[ 'Name' ] . ( empty( $row1[ 'KadastrNo' ] ) ? "" : " (" . $row1[ 'KadastrNo' ] . ")" ) . "</option>";
											}
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='QueryDate'>" . $fn[ 5 ] . ":</label></td>";
										echo "<td><input type=datetime-local name='QueryDate' id='QueryDate' value='" . date( 'Y-m-d\TH:i:s', strtotime( $row[ 'QueryDate' ] ) ) . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='QueryStatus'>" . $fn[ 6 ] . ":</label></td>";
										echo "<td><select name='QueryStatus' id='QueryStatus' width='100%'>";
											echo "<option value='NULL'>&nbsp;</option>";
											echo "<option " . ( ( $row[ 'QueryStatus' ] == 0 ) ? "selected " : "" ) . " value='0'>На рассмотрении (новый)&nbsp;&nbsp;&nbsp;</option>";
											echo "<option " . ( ( $row[ 'QueryStatus' ] == 1 ) ? "selected " : "" ) . " value='1'>Одобрено</option>";
											echo "<option " . ( ( $row[ 'QueryStatus' ] == 2 ) ? "selected " : "" ) . " value='1'>Отказано</option>";
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='QueryAnswer'>" . $fn[ 7 ] . ":</label></td>";
										echo "<td><input type=text name='QueryAnswer' id='QueryAnswer' value='" . $row[ 'QueryAnswer' ] . "'></td>";
									echo "</tr>";
								echo "</table>";
							?>
						</div>
						<div>
							<table width='100%'>
								<tr>
									<td>
										<input type="submit" class="confirm" value="Сохранить">
										<a href="../index.php?table=AccountsQuery">Отменить</a>
									</td>
								</tr>
							</table>
						</div>
					</form>
				</div>
            </div>
        </div>
		<br>
		
		<?php
		if ( $id != -1 ) {
			echo "<div class='container content'>";
				echo "<div class='main__left'>";
					echo "<div class='container'>";
						echo "<div class='content__title'>";
							echo "<h5>Рассмотрение запроса пользователя на добавление лицевого счета</h5>";
						echo "</div>";
						echo "<form class='' method='post' action='../create_update_query/update_accounts_query_status.php'>";
							echo "<div class='form_wrapper'>";
								echo "<input type='hidden' name='QueryId' id='QueryId' value='" . $id . "'>";
								echo "<input type='hidden' name='AccountName' id='AccountName' value='" . $row[ 'AccountName' ] . "'>";
								echo "<input type='hidden' name='UserId' id='UserId' value='" . $row[ 'UserId' ] . "'>";
								echo "<input type='hidden' name='QueryStatus' id='QueryStatus' value='1'>";
								echo "<input type='hidden' name='QueryAnswer' id='QueryAnswer' value=''>";
								echo "<table>";
									echo "<tr>";
										echo "<td><label for='AccountId'>Лицевой счет:</label></td>";
										echo "<td><select class='select_db' name='AccountId' id='AccountId' required>";
											$query1 = "SELECT 
														   PersonalAccounts.AccountId AS AccountId,
														   PersonalAccounts.Name AS Name
													   FROM PersonalAccounts AS PersonalAccounts
													   ORDER BY PersonalAccounts.Name";
											$result1 = mysqli_query( $link, $query1 ) or die( "Ошибка: " . mysqli_error( $link ) ); 
											
											while ( $row1 = mysqli_fetch_array( $result1 ) ) {
												echo "<option value='" . $row1[ 'AccountId' ] . "'>" . $row1[ 'Name' ] . "</option>";
											}
										echo "</select></td>";
									echo "</tr>";								
									echo "<tr>";
										echo "<td><label for='Access'>Режим доступа:</label></td>";
										echo "<td><input type=checkbox name='Access' id='Access'></td>";
									echo "</tr>";
								echo "</table>";
							echo "</div>";
							echo "<div>";
								echo "<table width='100%'>";
									echo "<tr><td>";
										echo "<input type='submit' class='confirm' value='Одобрено'>";
										echo "<a href='../index.php?table=AccountsQuery'>Отменить</a>";
									echo "</td></tr>";
								echo "</table>";
							echo "</div>";
						echo "</form>";
						echo "<br>";
						echo "<form class='' method='post' action='../create_update_query/update_accounts_query_status.php'>";
							echo "<div class='form_wrapper'>";
								echo "<input type='hidden' name='QueryId' id='QueryId' value='" . $id . "'>";
								echo "<input type='hidden' name='AccountName' id='AccountName' value='" . $row[ 'AccountName' ] . "'>";
								echo "<input type='hidden' name='UserId' id='UserId' value='" . $row[ 'UserId' ] . "'>";
								echo "<input type='hidden' name='QueryStatus' id='QueryStatus' value='2'>";
								echo "<table>";
									echo "<tr>";
										echo "<td><label for='QueryAnswer'>" . $fn[ 7 ] . ":</label></td>";
										echo "<td><input type=text name='QueryAnswer' id='QueryAnswer' value='" . $row[ 'QueryAnswer' ] . "' width='100%'></td>";
									echo "</tr>";
								echo "</table>";
							echo "</div>";
							echo "<div>";
								echo "<table width='100%'>";
									echo "<tr><td>";
										echo "<input type='submit' class='confirm' value='Отказано'>";
										echo "<a href='../index.php?table=AccountsQuery'>Отменить</a>";
									echo "</td></tr>";
								echo "</table>";
							echo "</div>";
						echo "</form>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		?>
    </div>
    <div class="footer">
        <div class="container"></div>
    </div>
</body>
</html>