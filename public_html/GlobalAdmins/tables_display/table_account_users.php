<div class="wrapper_left">
    <table id="data_table">
    	<tbody>
    		<tr id="tr_parent_0">
                <?php
					$fn = array( "Пользователь", "Лицевой счет", "Одобрен (активен)", "Режим доступа" );
				
                    foreach ( $fn as $nm ) {
						echo "<th>" .$nm. "</th>";
					}
                ?>
    		</tr>
			<?php
				$where = "";
			
				if ( isset( $_GET[ 'UserId' ] ) ) {
					$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountUsers.UserId = " . $_GET[ 'UserId' ] . " )";
				}
				
				if ( isset( $_GET[ 'AccountId' ] ) ) {
					$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountUsers.AccountId = " . $_GET[ 'AccountId' ] . " )";
				}

				mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
				$query  = "SELECT 
						       AccountUsers.UserId AS UserId,
							   IFNULL( Users.Login, '' ) AS UserLogin,
							   IFNULL( Users.Name, '' ) AS UserName,
						       AccountUsers.AccountId AS AccountId,
						       IFNULL( PersonalAccounts.Name, '' ) AS AccountName,
						       AccountUsers.Active AS Active,
						       AccountUsers.Access AS Access
						   FROM AccountUsers AS AccountUsers 
						   LEFT JOIN Users AS Users ON ( Users.UserId = AccountUsers.UserId )
						   LEFT JOIN PersonalAccounts AS PersonalAccounts ON ( PersonalAccounts.AccountId = AccountUsers.AccountId )
						   " . $where . " 
						   ORDER BY IFNULL( Users.Name, '' ),
						            IFNULL( PersonalAccounts.Name, '' )";
				$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) ); 
				$ct     = 1;
				
				if ( $result ) {
					while ( $row = mysqli_fetch_array( $result ) ) {
						echo "<tr id='tr_parent_" .$ct. "'>";
						echo "<td>" . $row[ 'UserName' ] . ( empty( $row[ 'UserName' ] ) ? $row[ 'UserLogin' ] : ( empty( $row[ 'UserLogin' ] ) ? "" : " (" . $row[ 'UserLogin' ] . ")" ) ) . "</td>";
						echo "<td>" . $row[ 'AccountName' ] . "</td>";
						echo "<td>" . ( ( $row[ 'Active' ] == 1 ) ? "✅" : "" ) . "</td>";
						echo "<td>" . ( ( $row[ 'Access' ] == 1 ) ? "✅" : "" ) . "</td>";
						echo "</tr>";
						
						$data[] = [ 'UserId'    => $row[ 'UserId' ],
						            'AccountId' => $row[ 'AccountId' ] ];
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
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_account_users.php?UserId=-1&AccountId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_account_users.php?UserId=" . $value[ 'UserId' ] . "&AccountId=" . $value[ 'AccountId' ] . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='./create_update_query/delete_account_users.php?UserId=" . $value[ 'UserId' ] . "&AccountId=" . $value[ 'AccountId' ] . "'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
