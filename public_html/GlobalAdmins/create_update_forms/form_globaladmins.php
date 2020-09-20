<?php
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_header.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_admin_header.php';
?>

<body>
	<?php
		$id  = $_GET[ 'GlobalAdminId' ];
		$fn  = $names_ar[ 'GlobalAdmins' ];
		$row = array( 'GlobalAdminId'         => -1,
					  'Login'                 => '',
					  'Password'              => '',
					  'Name'                  => '',
					  'Email'                 => '',
					  'Phone'                 => '',
					  'Comment'            	  => '');
		
		if ( $id != -1 ) {
			mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
			$query  = "SELECT 
						   GlobalAdmins.GlobalAdminId AS GlobalAdminId,
						   GlobalAdmins.Login AS Login,
						   GlobalAdmins.Password AS Password,
						   GlobalAdmins.Name AS Name,
						   GlobalAdmins.Email AS Email,
						   GlobalAdmins.Phone AS Phone,
						   GlobalAdmins.Comment AS Comment
					   FROM GlobalAdmins AS GlobalAdmins 
					   WHERE ( GlobalAdmins.GlobalAdminId = " . $id . " )
					   LIMIT 1";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
			
			if ( !$row = mysqli_fetch_array( $result ) ) {
				$row = array( 'GlobalAdminId'               => -1,
							  'Login'                 => '',
							  'Password'              => '',
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
						<h5>Пользователи</h5>
					</div>
					<form class = "" method = "post" action = "../create_update_query/update_query_globaladmins.php">
						<div class = "form_wrapper">
							<?php
								echo "<input type = 'hidden' name = 'GlobalAdminId' id = 'GlobalAdminId' value = '" . $id . "'>";
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
										echo "<td><label for = 'Name'>" . $fn[ 3 ] . ":</label></td>";
										echo "<td><input type=text name = 'Name' id = 'Name' value = '" . $row[ 'Name' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'Email'>" . $fn[ 4 ] . ":</label></td>";
										echo "<td><input type=email name = 'Email' id = 'Email' value = '" . $row[ 'Email' ] . "'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'Phone'>" . $fn[ 5 ] . ":</label></td>";
										echo "<td><input type=text name = 'Phone' id = 'Phone' value = '" . $row[ 'Phone' ] . "'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for = 'Comment'>" . $fn[ 6 ] . ":</label></td>";
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
										<a href = "../index.php?table=GlobalAdmins">Отменить</a>
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