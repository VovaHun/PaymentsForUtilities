<div class="wrapper_left">
    <table id="data_table">
    	<tbody>
    		<tr id="tr_parent_0">
                <?php
					$fn = array( "ID", "Данные запроса (лицевой счет, комментарий)", "Пользователь", "Управляющая компания", "Объект недвижимости", "Дата запроса", "Статус запроса", "Причина отказа" );
				
                    foreach ( $fn as $nm ) {
						echo "<th>" .$nm. "</th>";
					}
                ?>
    		</tr>
			<?php
				$where = "";
			
				if ( isset( $_GET[ 'UserId' ] ) ) {
					$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountsQuery.UserId = " . $_GET[ 'UserId' ] . " )";
				}
			
				if ( isset( $_GET[ 'CompanyId' ] ) ) {
					$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountsQuery.CompanyId = " . $_GET[ 'CompanyId' ] . " )";
				}
			
				if ( isset( $_GET[ 'ObjectId' ] ) ) {
					$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountsQuery.ObjectId = " . $_GET[ 'ObjectId' ] . " )";
				}

				
				mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
				$query  = "SELECT 
						       AccountsQuery.QueryId AS QueryId,
						       AccountsQuery.AccountName AS AccountName,
						       AccountsQuery.UserId AS UserId,
							   IFNULL( Users.Login, '' ) AS UserLogin,
							   IFNULL( Users.Name, '' ) AS UserName,
						       AccountsQuery.CompanyId AS CompanyId,
							   IFNULL( ManagementCompany.Name, '' ) AS CompanyName,
						       AccountsQuery.ObjectId AS ObjectId,
							   IFNULL( Objects.Name, '' ) AS ObjectName,
							   IFNULL( Objects.KadastrNo, '' ) AS ObjectKadastrNo,
						       DATE_FORMAT( AccountsQuery.QueryDate, '%d.%m.%Y %H:%i:%s' ) AS QueryDate,
						       AccountsQuery.QueryStatus AS QueryStatus,
						       AccountsQuery.QueryAnswer AS QueryAnswer
						   FROM AccountsQuery AS AccountsQuery 
						   LEFT JOIN Users AS Users ON ( Users.UserId = AccountsQuery.UserId )
						   LEFT JOIN ManagementCompany AS ManagementCompany ON ( ManagementCompany.CompanyId = AccountsQuery.CompanyId )
						   LEFT JOIN Objects AS Objects ON ( Objects.ObjectId = AccountsQuery.ObjectId )
						   " . $where . " 
						   ORDER BY AccountsQuery.QueryDate DESC";
				$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
				$ct     = 1;
				
				if ( $result ) {
					while ( $row = mysqli_fetch_array( $result ) ) {
						echo "<tr id='tr_parent_" .$ct. "'>";
						echo "<td>" . $row[ 'QueryId' ] . "</td>";
						echo "<td>" . $row[ 'AccountName' ] . "</td>";
						echo "<td>" . $row[ 'UserName' ] . ( empty( $row[ 'UserName' ] ) ? $row[ 'UserLogin' ] : ( empty( $row[ 'UserLogin' ] ) ? "" : " (" . $row[ 'UserLogin' ] . ")" ) ) . "</td>";
						echo "<td>" . $row[ 'CompanyName' ] . "</td>";
						echo "<td>" . $row[ 'ObjectName' ] . ( empty( $row[ 'ObjectKadastrNo' ] ) ? "" : " (" . $row[ 'ObjectKadastrNo' ] . ")" ) . "</td>";
						echo "<td>" . $row[ 'QueryDate' ] . "</td>";
						echo "<td>" . ( ( $row[ 'QueryStatus' ] == 0 ) ? "" : ( ( $row[ 'QueryStatus' ] == 1 ) ? "✅" : "❌" ) ) . "</td>";
						echo "<td>" . $row[ 'QueryAnswer' ] . "</td>";
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
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_accounts_query.php?QueryId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_accounts_query.php?QueryId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('AccountsQuery', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
