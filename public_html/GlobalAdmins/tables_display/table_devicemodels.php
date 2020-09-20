<div class="wrapper_left">
<table id="data_table">
	<tbody>
		<tr id='tr_parent_0'>
	<?php
		$ct=1;
		// вывод данных таблицы
		$query1 ="SELECT * FROM `DeviceModels` GROUP BY DeviceModels.Name";
		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 

		

		$fn = array("Модель приборов учета","Наименование модели", "Межповерочный интервал, мес.","	Производитель");

				for($i=1;$i<4;$i++)
				{
					echo "<th>".$fn[$i]."</th>";
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
				
				echo "<td>".$row1[1]."</td>"; /*Название модели*/
				echo "<td>".$row1[2]."</td>"; /*Межпроверночный интервал*/
				echo "<td>".$row1[3]."</td>"; /*Производитель*/
				
				
				echo "</tr>";
				$data[] = $row1[0];

			}
		}
	?>
	</tbody>
</table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_devicemodels.php?ModelId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_devicemodels.php?ModelId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('DeviceModels', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
