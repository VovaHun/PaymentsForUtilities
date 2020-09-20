<div class="wrapper_left">
<table id="data_table">
	<tbody>
		<tr id='tr_parent_0'>
		<?php
		    $where = "WHERE Devices.CompanyId = ".$company_admin['CompanyId'];
			
			if ( isset( $_GET[ 'DeviceId' ] ) ) {
				$where = $where . ( empty( $where ) ? "WHERE " : " AND " ) . "( DeviceEvents.DeviceId = " . $_GET[ 'DeviceId' ] . " )";
			}

			$ct=1;
			 mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		// вывод данных таблицы
			$query1 ="SELECT 
						
						DeviceEvents.DeviceId AS DeviceEventsId,
						DeviceEvents.EventType AS EventType,
						DeviceEvents.EventDate AS EventDate,
						IFNULL (DeviceEvents.Indications,'0') AS Indications,
						DeviceEvents.Using AS 'Using',
						Devices.DeviceId AS DeviceId, 
						Devices.Name AS Name
						FROM DeviceEvents AS DeviceEvents
						LEFT JOIN Devices AS Devices ON (DeviceEvents.DeviceId = Devices.DeviceId)
						" . $where . "
                        ORDER BY Name, EventDate,EventType ";

		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 

		$fn = array("Прибор учета","Тип события","Дата события","Показания на дату события",
			"Используется");

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
			
				switch ($row1['EventType']) {     /*Тип события*/
					case 1:
						echo "<td>Установка</td>";
						break;
					case 2:
						echo "<td>Демонтаж</td>";
						break;
					case 3:
						echo "<td>Отключение</td>";
						break;
					case 4:
						echo "<td>Начало проверки</td>";
						break;
					case 5:
						echo "<td>Окончание проверки</td>";
						break;
					
				}
				/*Смена формата даты*/
				 /*Дата события*/
				$date = strtotime($row1['EventDate']);			
				echo "<td>".date('d.m.Y H:i:s',$date)."</td>";
				echo "<td>".$row1['Indications']."</td>"; /*Показания на дату события*/
				echo "<td>" . ( (  $row1['Using'] == 1 ) ? "✅" : "" ) . "</td>";
				echo "</tr>";
				
				$data[] = ['DeviceId' => $row1['DeviceEventsId'],
				            'EventType' => $row1['EventType'], 
				           'EventDate' => $row1['EventDate']  ];
			}
		}
	?>
	</tbody>
</table>
</div>

<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_deviceevents.php?DeviceId=-1&EventType=-1&EventDate=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_deviceevents.php?DeviceId=" . $value['DeviceId'] . "&EventType= ".$value['EventType']."&EventDate=".$value['EventDate']."'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					//echo "<td width='20px'><a href='./create_update_query/delete_deviceevents.php?DeviceId=" . $value['DeviceId'] . "&EventType= ".$value['EventType']."&EventDate=".$value['EventDate']."'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";
					//echo "<td width='20px'><a href='#ex1' onclick=\"Delete('DeviceEvents', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					//echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>

