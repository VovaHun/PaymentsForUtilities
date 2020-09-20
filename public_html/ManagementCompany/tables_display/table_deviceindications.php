<div class="wrapper_left">
<table id="data_table">
	<tbody>
		<tr id='tr_parent_0'>
	<?php
	    $where = "WHERE Devices.CompanyId = ".$company_admin['CompanyId'];
			
			if ( isset( $_GET[ 'DeviceId' ] ) ) {
				$where = $where . ( empty( $where ) ? "WHERE " : " AND " ) . "( DeviceIndications.DeviceId = " . $_GET[ 'DeviceId' ] . " )";
			}

		$ct=1;
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		// вывод данных таблицы
		$query1 ="SELECT 
						
						DeviceIndications.DeviceId AS DeviceId,
						DeviceIndications.Date AS 'Date',
						IFNULL (DeviceIndications.Indications,0) AS Indications, 
						DeviceIndications.Fixed AS Fixed,
						Devices.Name AS Name
						FROM DeviceIndications AS DeviceIndications
						LEFT JOIN Devices AS Devices ON (Devices.DeviceId = DeviceIndications.DeviceId)
						" . $where . "
                        ORDER BY Name, DeviceIndications.Date DESC";

		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 


		$fn =  array("Прибор учета","Дата фиксации показания прибора учета", "Показания прибора учета","Зафиксировано");


				foreach($fn as $nm)
					{
					    echo "<th>".$nm."</th>";
					}
				?>						
		</tr>

	<?php
		


		if($result1)
		{
		    while($row1 = mysqli_fetch_array($result1)){
		    	echo "<tr id='tr_parent_".$ct."'>";
		    	$ct = $ct + 1;
				
				echo "<td>".$row1['Name']."</td>"; /*Прибор учета*/
			  
				
				/*Смена формата даты*/
				$date = strtotime($row1['Date']);			
				echo "<td>".date('d.m.Y H:i:s',$date)."</td>"; /*Дата события*/
				echo "<td>".number_format($row1['Indications'],2,'.','')."</td>";
			//	echo "<td>".$row1['Indications']."</td>"; /*Показания на дату события*/
				echo "<td>" . ( ( $row1['Fixed'] == 1 ) ? "✅" : "" ) . "</td>";
				

				echo "</tr>";	
				
			     $data[] = ['DeviceId' => $row1['DeviceId'], 'Date' => $row1['Date'] ];
	
			}
		}
	?>
	</tbody>
</table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_deviceindications.php?DeviceId=-1&Date=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_deviceindications.php?DeviceId=". $value['DeviceId']."&Date=" . $value['Date'] . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					//echo "<td width='20px'><a href='./create_update_query/delete_deviceindications.php?DeviceId=". $value['DeviceId']."&Date=" . $value['Date'] . "'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					//echo "<td width='20px'><a href='#ex1' onclick=\"Delete('DeviceIndications', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>


