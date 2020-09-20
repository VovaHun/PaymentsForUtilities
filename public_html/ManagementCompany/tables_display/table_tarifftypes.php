<div class = "wrapper_left">
    <table id = "data_table">
        <tbody>
            <tr id = 'tr_parent_0'>
    	<?php
            $ct=1;
    		// Вывод данных из таблиц
			$query1 = "SELECT  TariffTypes.TariffId, TariffTypes.Name, TariffTypes.IsNormative FROM TariffTypes ORDER BY TariffTypes.Name";
    		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    
    		$num_columns = $result1->field_count;
    
    		$fn = array("ID","Вид тарифа","Наименование","Является нормативом");
    
    	?>
    
                <th>ID</th>
                <th>Наименование</th>
                <th>Является нормативом</th>
    		</tr>
    	<?php
    		if($result1)
    		{
    		    while($row1 = mysqli_fetch_array($result1))
				{
    		    	echo "<tr id = 'tr_parent_".$ct."'>";
    				$ct = $ct + 1;
    				
    				for ($i = 0; $i < $num_columns-1; $i++) 
					{
    					echo "<td>".($row1[$i])."</td>";
    				}
					
				    echo "<td>" . ( ( $row1[2] == 1 ) ? "✅" : "" ) . "</td>";
    	
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
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_tarifftypes.php?TariffId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_tarifftypes.php?TariffId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
