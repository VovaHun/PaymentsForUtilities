<div class="wrapper_left">
    <table id="data_table">
        <tbody>
            <tr id='tr_parent_0'>
    	<?php
        	$where = "";
    			
    		if ( isset( $_GET[ 'TariffId' ] ) ) {
    			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( CommonDevices.TariffId = " . $_GET[ 'TariffId' ] . " )";
    		}
    
    		if ( isset( $_GET[ 'DeviceId' ] ) ) {
    			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( CommonDevices.DeviceId = " . $_GET[ 'DeviceId' ] . " )";
    		}
    		
        	    
            if ( isset( $_GET[ 'ServiceId' ] ) ) {
    			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( CommonDevices.ServiceId = " . $_GET[ 'ServiceId' ] . " )";
    		}
    		
    		if ( isset( $_GET[ 'ObjectId' ] ) ) {
    			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( CommonDevices.ObjectId = " . $_GET[ 'ObjectId' ] . " )";
    		}

            $ct=1;
    		// вывод данных таблицы
    		$query1 ="SELECT 
    	            	Objects.KadastrNo AS KadastrNo,
                        Objects.Name AS ObjectName, 
                        Services.Name AS SerName, 
                        Services.CompanyId AS CompanyId,
                        ManagementCompany.Name AS ManName,
                        TariffTypes.Name AS TarName, 
                        Devices.Name AS DevName, 
                        CommonDevices.Date AS ComDate, 
                        CommonDevices.Using AS ComUsing, 
                        CommonDevices.ObjectId AS ObjectId,
                        CommonDevices.ServiceId AS ServiceId,
                        CommonDevices.TariffId AS TariffId,
                        CommonDevices.DeviceId AS DeviceId
                      FROM CommonDevices
                      LEFT JOIN Objects AS Objects ON (CommonDevices.ObjectId = Objects.ObjectId)
                      LEFT JOIN Services AS Services ON (CommonDevices.ServiceId = Services.ServiceId)
                      LEFT JOIN TariffTypes AS TariffTypes ON (CommonDevices.TariffId = TariffTypes.TariffId)
                      LEFT JOIN Devices AS Devices ON (CommonDevices.DeviceId = Devices.DeviceId)
                      LEFT JOIN ManagementCompany AS ManagementCompany ON (ManagementCompany.CompanyId = Services.CompanyId)
                      	" . $where . "
                      ORDER BY ObjectName, TarName";

    		          $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    
    		          $fn = array("Объект недвижимости","Коммунальная услуга","  Вид тарифа","Прибор учета","Дата начала действия","Используется");
    
    		
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
    				echo "<td>" . $row1[ 'ObjectName' ] . ( empty( $row1[ 'KadastrNo' ] ) ? "" : " (" . $row1[ 'KadastrNo' ] . ")" ) . "</td>";
    				
    				echo "<td>" . $row1[ 'SerName' ] . ( empty( $row1[ 'ManName' ] ) ? "" : " (" . $row1[ 'ManName' ] . ")" ) . "</td>";
                    
                    echo "<td>".$row1['TarName']."</td>";
                    echo "<td>".$row1['DevName']."</td>";
                    /*Смена формата даты*/
                    if(is_null($row1['ComDate'])){
                        echo "<td>Бессрочно</td>";
                    }
                    else{
                        $date = strtotime($row1['ComDate']);            
                        echo "<td>".date('d.m.Y',$date)."</td>";
                    }
                    echo "<td>" . ( ( $row1['ComUsing'] == 1 ) ? "✅" : "" ) . "</td>"; 
               
                    
    				echo"</tr>";
    				
    				$data[] = [     'ObjectId'    => $row1[ 'ObjectId' ],
                                    'ServiceId'    => $row1[ 'ServiceId' ],
                                    'TariffId'     => $row1[ 'TariffId' ],
                                    'DeviceId'     => $row1[ 'DeviceId' ],
                                    'Date'         => $row1[ 'ComDate' ]];
    			}
    		}
    	?>
    	</tbody>
    </table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_commondevices.php?ObjectId=-1&ServiceId=-1&TariffId=-1&DeviceId=-1&Date=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_commondevices.php?ObjectId=" . $value[ 'ObjectId' ] . "&ServiceId=".$value[ 'ServiceId' ]."&TariffId=".$value[ 'TariffId' ]."&DeviceId=".$value[ 'DeviceId' ]."&Date=".$value[ 'Date' ]."'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex3' onclick=\"DeleteCommonDevices('CommonDevices', " . $value[ 'ObjectId' ] . ",".$value[ 'ServiceId' ].",".$value[ 'TariffId' ].", ".$value[ 'DeviceId' ].",'".$value[ 'Date' ]."')\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					//echo "<td width='20px'><a href='#ex1' onclick=\"Delete('CommonDevices', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modalCommonDevices.php';
		?>
	</table>
</div>
<script>
    
    function DeleteCommonDevices(table, elem, elem2, elem3,elem4,elem5) {
        $('#objectId').val(elem);
        $('#popup_table').val(table);
        $('#serviceId').val(elem2);
        $('#tariffId').val(elem3);
        $('#deviceId').val(elem4);
        $('#dateId').val(elem5);
}
</script>