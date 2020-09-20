<div class="wrapper">
<table id="data_table">
	<tbody>
		<tr id='tr_parent_0'>
	<?php
    	$where = "";
    		
    	if ( isset( $_GET[ 'CompanyId' ] ) ) {
    		$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( PersonalAccounts.CompanyId = " . $_GET[ 'CompanyId' ] . " )";
    	}
    	
    	if ( isset( $_GET[ 'ObjectId' ] ) ) {
    		$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( PersonalAccounts.ObjectId = " . $_GET[ 'ObjectId' ] . " )";
    	}
    	
    	if ( isset( $_GET[ 'AbonentId' ] ) ) {
    		$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( PersonalAccounts.AbonentId = " . $_GET[ 'AbonentId' ] . " )";
    	}
	
		$ct=1;
		// вывод данных таблицы
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$query1 ="SELECT 
					PersonalAccounts.AccountId AS AccountId, 
					PersonalAccounts.Name AS Name, 
					ManagementCompany.Name AS MenagName, 
					Abonents.Name AS AbonentName, 
					Objects.Name AS ObjectName, 
					Objects.KadastrNo AS KadastrNo,
					PersonalAccounts.StartDate AS StartDate, 
					PersonalAccounts.EndDate AS EndDate, 
					PersonalAccounts.Using AS 'Using'
		    		FROM PersonalAccounts AS PersonalAccounts
		    		LEFT JOIN ManagementCompany AS ManagementCompany ON (PersonalAccounts.CompanyId = ManagementCompany.CompanyId)
		    		LEFT JOIN Abonents AS Abonents ON (PersonalAccounts.AbonentId = Abonents.AbonentId)
		    		LEFT JOIN Objects AS Objects ON (PersonalAccounts.ObjectId =  Objects.ObjectId )
		    		" . $where . "
		    		ORDER BY MenagName, Name ";

		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

		$fn =  array("Id","Номер лицевого счета","Управляющая компания","Абонент","Объект недвижимости","Дата начала действия","Дата окончания действия","Используется (активен)");

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

		    	echo "<td>".$row1['AccountId']."</td>"; /* ID лицевого счета*/
				echo "<td>".$row1['Name']."</td>"; /* Название */
				echo "<td>".$row1['MenagName']."</td>"; //УК
				echo "<td>".$row1['AbonentName']."</td>"; //Абонент
				
				
				echo "<td>" . $row1[ 'ObjectName' ] . ( empty( $row1[ 'KadastrNo' ] ) ? "" : " (" . $row1[ 'KadastrNo' ] . ")" ) . "</td>";
				//echo "<td>".$row1['ObjectName']."</td>"; //Объект недвиж
				if($row1['StartDate']== NULL){
					echo "<td>Еще не установлена</td>";
				}
				else{
					$date = strtotime($row1['StartDate']);			
				echo "<td>".date('d.m.Y',$date)."</td>";  //Дата начала
				}
				if($row1['EndDate']== NULL){
					echo "<td>Еще не установлена</td>";
				}
				else{
					$date = strtotime($row1['EndDate']);			
				echo "<td>".date('d.m.Y',$date)."</td>";  //Дата окончания
				}

				echo "<td>" . ( ( $row1[ 'Using' ] == 1 ) ? "✅" : "" ) . "</td>";

				$data[] = $row1['AccountId'];
				echo "</tr>";
			}
		}
	?>
	</tbody>
</table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_personalaccount.php?AccountId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_personalaccount.php?AccountId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('PersonalAccounts', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
