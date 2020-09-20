<div class="wrapper_left">
<table id="data_table">
	<tbody>
		<tr id='tr_parent_0'>
	<?php
	    $where = "";
			
			/*if ( isset( $_GET[ 'UnitId' ] ) ) {
				$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( Services.UnitId = " . $_GET[ 'UnitId' ] . " )";
			}*/
			
		$ct=1;
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		// вывод данных таблицы
				$query1 ="SELECT 
							Debt.AccountId AS AccountId,
							Debt.Period AS Period,
							Debt.Summa AS Summa,
							IFNULL(PersonalAccounts.Name,'') AS PersName
							FROM Debt AS Debt 
							LEFT JOIN PersonalAccounts AS PersonalAccounts ON (PersonalAccounts.AccountId = Debt.AccountId)
							" . $where . "
                            ORDER BY PersName, Period";

       

		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

		$fn = array("Лицевой счёт","Период", "Сумма");

				foreach($fn as $nm)
					{
					    echo "<th>".$nm."</th>";
					}
				?>						
		</tr>

	<?php
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		
		if($result1)
		{
		    while($row1 = mysqli_fetch_array($result1)){
		    	echo "<tr id='tr_parent_".$ct."'>";
		    	$ct = $ct + 1;

		    	 /* ID комунальной услуги*/
		    	echo "<td>".$row1['PersName']."</td>";
				echo "<td>".$row1['Period']."</td>";
				echo "<td>".$row1['Summa']."</td>";
				//echo "<td>" . ( ( $row1[ 'IsComposite' ] == 1 ) ? "✅" : "" ) . "</td>";

				echo "</tr>";
				
                $data[] = ["AccountId" => $row1['AccountId'], 
                           "Period" => $row1['Period'], 
                           "Summa" => $row1['Summa']];
			}
		}
	?>
	</tbody>
</table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_services.php?AccountId=-1&Method=-1&'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_services.php?AccountId=" . $value['AccountId'] . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('AccountId', " . $value['AccountId'] . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>