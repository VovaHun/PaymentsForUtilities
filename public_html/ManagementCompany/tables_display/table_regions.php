<div class="wrapper_left" style="width: 100%">
    <table id="data_table">
        <tbody>
            <tr id='tr_parent_0'>
    	       <?php
					$ct=1;
					// вывод данных таблицы
					$query1 = "SELECT * FROM Regions ORDER BY RegionСode";
					$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));    
					$fn = array("ID","Код региона","Наименование");
		
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
    				echo "<td>".($row1[0])."</td>";
    				echo "<td>".($row1[1])."</td>";
                    echo "<td>".($row1[2])."</td>";
                    echo "</tr>";
                    
                    $data[] = $row1[0];
    			}
    		}
    	?>
    	</tbody>
    </table>
</div>