<?php
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_header.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_admin_header.php';
?>

<body>
	<?php
		$id  = $_GET[ 'AdminId' ];
		$fn  = $names_ar[ 'Admins' ];
		$row = array( 'AdminId'               => -1,
					  'Login'                 => '',
					  'Password'              => '',
					  'CompanyId'             => -1,
					  'Name'                  => '',
					  'Email'                 => '',
					  'Phone'                 => '',
					  'Comment'            	  => '');
		
		if ( $id != -1 ) {
			mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
			$query  = "SELECT 
						   Admins.AdminId AS AdminId,
						   Admins.Login AS Login,
						   Admins.Password AS Password,
						   Admins.CompanyId AS CompanyId,
						   Admins.Name AS Name,
						   Admins.Email AS Email,
						   Admins.Phone AS Phone,
						   Admins.Comment AS Comment
					   FROM Admins AS Admins 
					   WHERE ( Admins.AdminId = " . $id . " )
					   LIMIT 1";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
			
			if ( !$row = mysqli_fetch_array( $result ) ) {
				$row = array( 'AdminId'               => -1,
							  'Login'                 => '',
							  'Password'              => '',
							  'CompanyId'             => -1,
							  'Name'                  => '',
							  'Email'                 => '',
							  'Phone'                 => '',
							  'Comment'            	  => '');
			}
		}
	?>
	
    <div class = "main">
        <div class = "container content">
            <div class = "main__left">
				<div class = "container">
					<div class = "content__title">
						<h5>Администраторы</h5>
					</div>
					<form class = "" method = "post" action = "../create_update_query/update_query_admins.php">
						<div class = "form_wrapper">
							<?php
								echo "<input type = 'hidden' name = 'AdminId' id = 'AdminId' value = '" . $id . "'>";
								echo "<table>";
									echo "<tr>";
										echo "<td><label for = 'Login'>" . $fn[ 1 ] . ":</label></td>";
										echo "<td><input type=text name = 'Login' id = 'Login' value = '" . $row[ 'Login' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'Password'>" . $fn[ 2 ] . ":</label></td>";
										echo "<td><input type=password name = 'Password' id = 'Password' value = '" . $row[ 'Password' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'CompanyId'>" . $fn[ 3 ] . ":</label></td>";
										echo "<td><select class = 'select_db' name = 'CompanyId' id = 'CompanyId'>";
										    echo "<option value = 'NULL'>Не указано</option>";
											$query1 = "SELECT 
														   ManagementCompany.CompanyId AS CompanyId,
														   ManagementCompany.FullName AS FullName
													   FROM ManagementCompany AS ManagementCompany
													   ORDER BY ManagementCompany.Name";
											$result1 = mysqli_query( $link, $query1 ) or die( "Ошибка: " . mysqli_error( $link ) ); 
											
											while ($row1 = mysqli_fetch_array($result1)) 
											{
												if($row1['CompanyId']==$row['CompanyId'])
												{ 
													echo "<option selected value = ".$row1['CompanyId'].">".$row1['FullName']." </option> "; 
												}
												else
												{
													echo "<option  value = ".$row1['CompanyId'].">".$row1['FullName']." </option> ";  
												}
											}
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'Name'>" . $fn[ 4 ] . ":</label></td>";
										echo "<td><input type=text name = 'Name' id = 'Name' value = '" . $row[ 'Name' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'Email'>" . $fn[ 5 ] . ":</label></td>";
										echo "<td><input type=email name = 'Email' id = 'Email' value = '" . $row[ 'Email' ] . "'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'Phone'>" . $fn[ 6 ] . ":</label></td>";
										echo "<td><input type=text name = 'Phone' id = 'Phone' value = '" . $row[ 'Phone' ] . "'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'Comment'>" . $fn[ 7 ] . ":</label></td>";
										echo "<td><input type=text name = 'Comment' id = 'Comment' value = '" . $row[ 'Comment' ] . "'></td>";
									echo "</tr>";
								echo "</table>";
							?>
						</div>
						<div>
							<table width = '100%'>
								<tr>
									<td>
										<input type = "submit" class = "confirm" value = "Сохранить">
										<a href = "../index.php?table=Admins">Отменить</a>
									</td>
								</tr>
							</table>
						</div>
					</form>
				</div>
            </div>
        </div>
    </div>
    <div class = "footer">
        <div class = "container"></div>
    </div>
</body>
</html>