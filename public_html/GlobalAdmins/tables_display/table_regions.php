<div class="wrapper_left" style="width: 100%">
    <table id="data_table">
        <tbody>
            <tr id='tr_parent_0'>
    	       <?php
                $ct=1;
    		      // вывод данных таблицы
    		    $query1 ="SELECT * FROM Regions ORDER BY RegionСode";
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
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_regions.php?RegionId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_regions.php?RegionId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('Regions', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>