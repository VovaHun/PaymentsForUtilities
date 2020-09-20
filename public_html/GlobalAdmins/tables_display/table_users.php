<div class="wrapper_left">
    <table id="data_table">
    	<tbody>
    		<tr id="tr_parent_0">
                <?php
					$fn = array( "ID", "Логин", "Пароль", "Фамилия, имя, отчество", "Пол", "Электронная почта", "Согласие на получение уведомлений по электронной почте", "Телефон(ы)", "Согласие на получение SMS уведомлений", "Обращение", "Комментарий", "Согласие на обработку персональных данных", "Бот социальной сети", "Пользователь социальной сети", "Согласие на получение уведомлений в социальных сетях", "Дата регистрации" );
				
                    foreach ( $fn as $nm ) {
						echo "<th>" .$nm. "</th>";
					}
                ?>
    		</tr>
			<?php
				$where = "";
			
				if ( isset( $_GET[ 'BotId' ] ) ) {
					$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( Users.BotId = " . $_GET[ 'BotId' ] . " )";
				}
				
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
						       IFNULL( Bots.BotType, 0 ) AS BotType,
						       IFNULL( Bots.SocialId, 0 ) AS BotSocialId,
						       IFNULL( Bots.Name, '' ) AS BotName,
						       Users.SocialId AS SocialId,
						       Users.SocialNotifications AS SocialNotifications,
						       DATE_FORMAT( Users.RegistrationDate, '%d.%m.%Y %H:%i:%s' ) AS RegistrationDate
						   FROM Users AS Users 
						   LEFT JOIN Bots AS Bots ON ( Bots.BotId = Users.BotId )
						   " . $where . " 
						   ORDER BY Users.Name,
						            Users.Login";
				$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
				$ct     = 1;
				
				if ( $result ) {
					while ( $row = mysqli_fetch_array( $result ) ) {
						echo "<tr id='tr_parent_" .$ct. "'>";
						echo "<td>" . $row[ 'UserId' ] . "</td>";
						echo "<td>" . $row[ 'Login' ] . "</td>";
						echo "<td>" . $row[ 'Password' ] . "</td>";
						echo "<td>" . $row[ 'Name' ] . "</td>";
						echo "<td>" . ( ( $row[ 'Gender' ] == 1 ) ? "М" : ( ( $row[ 'Gender' ] == 2 ) ? "Ж" : "" ) ) . "</td>";
						echo "<td>" . $row[ 'Email' ] . "</td>";
						echo "<td>" . ( ( $row[ 'EmailNotifications' ] == 1 ) ? "✅" : "" ) . "</td>";
						echo "<td>" . $row[ 'Phone' ] . "</td>";
						echo "<td>" . ( ( $row[ 'PhoneNotifications' ] == 1 ) ? "✅" : "" ) . "</td>";
						echo "<td>" . $row[ 'Appeal' ] . "</td>";
						echo "<td>" . $row[ 'Comment' ] . "</td>";
						echo "<td>" . ( ( $row[ 'ConsentOnPersonalData' ] == 1 ) ? "✅" : "" ) . "</td>";
						echo "<td>" . ( ( $row[ 'BotType' ] == 1 ) ? "Telegram, " : ( ( $row[ 'BotType' ] == 2 ) ? "VK, " : ( ( $row[ 'BotType' ] == 3 ) ? "WhatsApp, " : "" ) ) ) . $row[ 'BotName' ] . "</td>";
						echo "<td>" . $row[ 'SocialId' ] . "</td>";
						echo "<td>" . ( ( $row[ 'SocialNotifications' ] == 1 ) ? "✅" : "" ) . "</td>";
						echo "<td>" . $row[ 'RegistrationDate' ] . "</td>";
						echo "</tr>";
						
						$data[] = $row[ 'UserId' ];
						$ct     = $ct + 1;
					}
				}
			?>
    	</tbody>
    </table>
</div>

<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_users.php?UserId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_users.php?UserId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('Users', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
