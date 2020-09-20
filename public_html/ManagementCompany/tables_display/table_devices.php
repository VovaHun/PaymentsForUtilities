<div class="wrapper">
<table id="data_table">
	<tbody>
		<tr id='tr_parent_0'>
	<?php
	    $where = "WHERE Devices.CompanyId = ".$company_admin['CompanyId'];
			
		if ( isset( $_GET[ 'CompanyId' ] ) ) {
			$where = $where . ( empty( $where ) ? "WHERE " : " AND " ) . "( Devices.CompanyId = " . $_GET[ 'CompanyId' ] . " )";
		}
	
		if ( isset( $_GET[ 'ModelId' ] ) ) {
			$where = $where . ( empty( $where ) ? "WHERE " : " AND " ) . "( Devices.ModelId = " . $_GET[ 'ModelId' ] . " )";
		}

		$ct=1;
		// вывод данных таблицы
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$query1 ="SELECT 
					Devices.DeviceId AS DeviceId,
					Devices.Name AS Name,
					Devices.ReleaseDate AS ReleaseDate,
					Devices.StartIndications AS StartIndications,
					Devices.ModelId AS ModelId,
					Devices.NextDateCheck AS NextDateCheck, 
					Devices.CompanyId AS CompanyId, 
					DeviceModels.Name AS ModelName,
					ManagementCompany.Name AS ManegName
		    		FROM Devices AS Devices
		    		LEFT JOIN  DeviceModels AS DeviceModels ON (Devices.ModelId = DeviceModels.ModelId)
		    		LEFT JOIN  ManagementCompany AS ManagementCompany ON (ManagementCompany.CompanyId = Devices.CompanyId)
		    		" . $where . "
					ORDER BY ManegName, Name ";

		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 

		$fn = array("Прибор учета","Наименование прибора учета или заводской номер","Дата выпуска","Начальные показания","Модель прибора учета","Дата следующей поверки","Управляющая компания");

	
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
		    	echo "<td>".$row1['DeviceId']."</td>"; /* ID прибора учета*/
				echo "<td>".$row1['Name']."</td>"; /* Название прибора учета*/
				$date = strtotime($row1['ReleaseDate']);			
				echo "<td>".date('d.m.Y',$date)."</td>"; /*Дата выпуска*/
				echo "<td>".$row1['StartIndications']."</td>";/*Начальные показания*/			
				echo "<td>".$row1['ModelName']."</td>";/*Модель*/
				$date = strtotime($row1['NextDateCheck']);			
				echo "<td>".date('d.m.Y',$date)."</td>"; /*Дата следующей проверки*/
				echo "<td>".$row1['ManegName']."</td>";
				
				
				$data[] = $row1['DeviceId'];
	?>
	
				
				
				</tr>
	<?php
			}
		}
	?>
	</tbody>
</table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_devices.php?DeviceId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_devices.php?DeviceId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					//echo "<td width='20px'><a href='#ex1' onclick=\"Delete('Devices', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
