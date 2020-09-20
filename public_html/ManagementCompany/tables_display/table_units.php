<div class = "wrapper_left">
    <table id = "data_table">
        <tbody>
            <tr id = 'tr_parent_0'>
    	<?php
            $ct=1;
    		// Вывод данных из таблиц
			$query1 = "SELECT * FROM Units ORDER BY Units.Name";
    		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    
    
    		$fn = array( "ID","Наименование");
    
    	?>
    	
                <th>ID</th>
                <th>Наименование</th>
    		</tr>
    	<?php
    		if($result1)
    		{
    		    while($row1 = mysqli_fetch_array($result1))
				{
    		    	echo "<tr id = 'tr_parent_".$ct."'>";
    				$ct = $ct + 1;
    				
    				for ($i = 0; $i < 2; $i++) 
					{
					    
    					echo "<td>".($row1[$i])."</td>";
    				}
    
    				echo "</tr>";
                    $data[] = $row1['0'];
    			}
    		}
    		
    		
    	?>
    	</tbody>
    </table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_units.php?UnitId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		
	</table>
</div>