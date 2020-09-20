<div class="wrapper_left">
    <table id="data_table">
    	<tbody>
    		<tr id="tr_parent_0">
                <?php
					$fn = array( "ID", "Логин", "Пароль", "Фамилия, имя, отчество", "Пол", "Электронная почта", "Согласие на получение уведомлений по электронной почте", "Телефон(ы)", "Согласие на получение SMS уведомлений", "Обращение", "Комментарий", "Согласие на обработку персональных данных", "Бот социальной сети", "Пользователь социальной сети", "Согласие на получение уведомлений в социальных сетях", "Дата запроса" );
				
                    foreach ( $fn as $nm ) {
						echo "<th>" .$nm. "</th>";
					}
                ?>
    		</tr>
			<?php
				$where = "";
			
				if ( isset( $_GET[ 'BotId' ] ) ) {
					$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( UsersQuery.BotId = " . $_GET[ 'BotId' ] . " )";
				}
				
				mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
				$query  = "SELECT 
						       UsersQuery.QueryId AS QueryId,
						       UsersQuery.Login AS Login,
						       UsersQuery.Password AS Password,
						       UsersQuery.Name AS Name,
						       UsersQuery.Gender AS Gender,
						       UsersQuery.Email AS Email,
						       UsersQuery.EmailNotifications AS EmailNotifications,
						       UsersQuery.Phone AS Phone,
						       UsersQuery.PhoneNotifications AS PhoneNotifications,
						       UsersQuery.AppealType AS AppealType,
						       UsersQuery.Appeal AS Appeal,
						       UsersQuery.Comment AS Comment,
						       UsersQuery.ConsentOnPersonalData AS ConsentOnPersonalData,
						       IFNULL( Bots.BotType, 0 ) AS BotType,
						       IFNULL( Bots.SocialId, 0 ) AS BotSocialId,
						       IFNULL( Bots.Name, 0 ) AS BotName,
						       UsersQuery.SocialId AS SocialId,
						       UsersQuery.SocialNotifications AS SocialNotifications,
						       DATE_FORMAT( UsersQuery.QueryDate, '%d.%m.%Y %H:%i:%s' ) AS QueryDate
						   FROM UsersQuery AS UsersQuery 
						   LEFT JOIN Bots AS Bots ON ( Bots.BotId = UsersQuery.BotId )
						   " . $where . " 
						   ORDER BY UsersQuery.QueryDate DESC";
				$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
				$ct     = 1;
				
				if ( $result ) {
					while ( $row = mysqli_fetch_array( $result ) ) {
						echo "<tr id='tr_parent_" .$ct. "'>";
						echo "<td>" . $row[ 'QueryId' ] . "</td>";
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
						echo "<td>" . $row[ 'QueryDate' ] . "</td>";
						echo "</tr>";
						
						$data[] = $row[ 'QueryId' ];
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
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_users_query.php?QueryId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_users_query.php?QueryId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('UsersQuery', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
