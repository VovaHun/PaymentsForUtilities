<div class="wrapper_left">
    <table id="data_table">
    	<tbody>
    		<tr id="tr_parent_0">
                <?php
					$fn = array( "ID", "Тип", "Идентификатор бота", "Наименование бота", "Выданный токен" );
					
                    foreach ( $fn as $nm ) {
						echo "<th>" .$nm. "</th>";
					}
                ?>
    		</tr>
			<?php
				mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
				$query  = "SELECT 
				               Bots.BotId AS BotId,
				               Bots.BotType AS BotType,
				               Bots.SocialId AS SocialId,
				               Bots.Name AS Name,
				               Bots.Token AS Token
						   FROM Bots AS Bots
						   ORDER BY Bots.BotType,
						            Bots.Name";
				$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
				$ct     = 1;
				
				if ($result) {
					while ( $row = mysqli_fetch_array($result) ) {
						echo "<tr id='tr_parent_" .$ct. "'>";
						echo "<td>" . $row[ 'BotId' ] . "</td>";
						echo "<td>" . ( ( $row[ 'BotType' ] == 1 ) ? "Telegram" : ( ( $row[ 'BotType' ] == 2 ) ? "VK" : ( ( $row[ 'BotType' ] == 3 ) ? "WhatsApp" : "" ) ) ) . "</td>";
						echo "<td>" . $row[ 'SocialId' ] . "</td>";
						echo "<td>" . $row[ 'Name' ] . "</td>";
						echo "<td>" . $row[ 'Token' ] . "</td>";
						echo "</tr>";
						
						$data[] = $row[ 'BotId' ];
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
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_bots.php?BotId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_bots.php?BotId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('Bots', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
