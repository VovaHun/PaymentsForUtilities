<div class="wrapper_left">
    <table id="data_table">
        <tbody>
            <tr id='tr_parent_0'>
    	<?php
    	    $where = "";
    	    
            if ( isset( $_GET[ 'ServiceId' ] ) ) {
    			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( ModelFunctions.ServiceId = " . $_GET[ 'ServiceId' ] . " )";
    		}
    		
    		if ( isset( $_GET[ 'ModelId' ] ) ) {
    			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( ModelFunctions.ModelId = " . $_GET[ 'ModelId' ] . " )";
    		}

            $ct=1;
    		// вывод данных таблицы
    		$query1 ="SELECT 
                        DeviceModels.Name AS DevName, 
                        IFNULL( TariffTypes.Name,'') AS TarName,
                        IFNULL (ModelFunctions.Using,0) AS ModUsing,
                        ModelFunctions.ModelId AS ModelId,
                        ModelFunctions.TariffId as TariffId
                      FROM ModelFunctions
                      LEFT JOIN DeviceModels AS DeviceModels ON (ModelFunctions.ModelId = DeviceModels.ModelId)
                      LEFT JOIN TariffTypes AS TariffTypes ON (TariffTypes.TariffId = ModelFunctions.TariffId)
                      " . $where . "
                      ORDER BY ModelId, TarName";


    		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    
    
    		$fn = array("Наименование модели","Вид тарифа" ,"Использование");
    
  
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
    				
    				echo "<td>".$row1['DevName']."</td>";
                    echo "<td>".$row1['TarName']."</td>";
                   
                    echo "<td>" . ( ( $row1['ModUsing'] == 1 ) ? "✅" : "" ) . "</td>";
    				echo"</tr>";
    				
    				$data[] = [  'ModelId' => $row1['ModelId'],
                                 'TariffId' => $row1['TariffId']];
    			}
    		}
    	?>
    	</tbody>
    </table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_modelfunctions.php?ModelId=-1&TariffId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_modelfunctions.php?ModelId=" . $value['ModelId'] . "&TariffId=".$value['TariffId']."'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex3' onclick=\"DeleteModelFunct('ModelFunctions', " . $value['ModelId'] . ",".$value[ 'TariffId' ].")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
				//	echo "<td width='20px'><a href='#ex1' onclick=\"Delete('ModelFunctions', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

		        require_once '../includes/modalModelFunctions.php';
		?>
	</table>
</div>
<script>
    
    function DeleteModelFunct(table, elem, elem2) {
        $('#modelId').val(elem);
        $('#popup_table').val(table);
        $('#tariffId').val(elem2);
 }
</script>





