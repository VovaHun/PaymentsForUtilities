<?php
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_header.php';
	require $_SERVER[ 'DOCUMENT_ROOT'] . '/GlobalAdmins/_admin_header.php';
?>

<body>
	<?php
		$id  = $_GET[ 'BotId' ];
		$fn  = $names_ar[ 'Bots' ];
		$row = array( 'BotId'    => -1, 
		              'BotType'  => '', 
					  'SocialId' => '', 
					  'Name'     => '', 
					  'Token'    => '' );
		
		if ( $id != -1 ) {
			mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
			$query = "SELECT 
						  Bots.BotId AS BotId,
						  Bots.BotType AS BotType,
						  Bots.SocialId AS SocialId,
						  Bots.Name AS Name,
						  Bots.Token AS Token
					  FROM Bots AS Bots
					  WHERE ( Bots.BotId = " . $id . " )
					  LIMIT 1";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
			
			if ( !$row = mysqli_fetch_array( $result ) ) {
				$row = array( 'BotId'    => -1, 
							  'BotType'  => '', 
							  'SocialId' => '', 
							  'Name'     => '', 
							  'Token'    => '' );
			}
		}		
	?>
	
    <div class="main">
        <div class="container content">
            <div class="main__left">
				<div class="container">
					<div class="content__title">
						<h5>Бот социальных сетей</h5>
					</div>
					<form class="" method="post" action="../create_update_query/update_bots.php">
						<div class="form_wrapper">
							<?php
								echo "<input type='hidden' name='BotId' id='BotId' value='" . $id . "'>";
								echo "<table>";
									echo "<tr>";
										echo "<td><label for='BotType'>" . $fn[ 1 ] . ":</label></td>";
										echo "<td><select name='BotType' id='BotType' required>";
											echo "<option " . ( ( $row[ 'BotType' ] == 1 ) ? "selected " : "" ) . " value='1'>Telegram</option>";
											echo "<option " . ( ( $row[ 'BotType' ] == 2 ) ? "selected " : "" ) . " value='2'>VK</option>";
											echo "<option " . ( ( $row[ 'BotType' ] == 3 ) ? "selected " : "" ) . " value='3'>WhatsApp</option>";
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='SocialId'>" . $fn[ 2 ] . ":</label></td>";
										echo "<td><input type=text name='SocialId' id='SocialId' value='" . $row[ 'SocialId' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Name'>" . $fn[ 3 ] . ":</label></td>";
										echo "<td><input type=text name='Name' id='Name' value='" . $row[ 'Name' ] . "' required></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><label for='Token'>" . $fn[ 4 ] . ":</label></td>";
										echo "<td><input type=text name='Token' id='Token' value='" . $row[ 'Token' ] . "' required></td>";
									echo "</tr>";
								echo "</table>";
							?>
						</div>
						<div>
							<table width='100%'>
								<tr>
									<td>
										<input type="submit" class="confirm" value="Сохранить">
										<a href="../index.php?table=Bots">Отменить</a>
									</td>
									<td align='right'>
									<?php
										if ( ( $id != -1 ) && ( $row[ 'BotType' ] == 1 ) ) {
											echo "<a href='form_bots_setwebhook.php?BotId=" . $row[ 'BotId' ] . "&SocialId=" . $row[ 'SocialId' ] . "&Token=" . $row[ 'Token' ] . "'>Активировать</a>";
											echo "&nbsp;&nbsp;&nbsp;";
											echo "<a href='form_bots_deletewebhook.php?BotId=" . $row[ 'BotId' ] . "&SocialId=" . $row[ 'SocialId' ] . "&Token=" . $row[ 'Token' ] . "'>Деактивировать</a>";
										}
									?>
									</td>
								</tr>
								<?php
									if ( $id != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr><td>";
										echo "<a href='../index.php?table=UsersQuery&BotId=" . $row[ 'BotId' ] . "' target='_blank'>Запросы пользователей на регистрацию</a>";
										echo "&nbsp;&nbsp;&nbsp;";
										echo "<a href='../index.php?table=Users&BotId=" . $row[ 'BotId' ] . "' target='_blank'>Пользователи</a>";
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