<div class="wrapper_left">
    <table id="data_table">
    	<tbody>
    		<tr id='tr_parent_0'>
                <?php
                    $where = "";
			
        			if ( isset( $_GET[ 'TariffId' ] ) ) {
        				$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountDevices.TariffId = " . $_GET[ 'TariffId' ] . " )";
        			}
        			
        			if ( isset( $_GET[ 'DeviceId' ] ) ) {
            			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountDevices.DeviceId = " . $_GET[ 'DeviceId' ] . " )";
            		}
            		
            		if ( isset( $_GET[ 'ServiceId' ] ) ) {
            			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountDevices.ServiceId = " . $_GET[ 'ServiceId' ] . " )";
            		}
            		
            		if ( isset( $_GET[ 'AccountId' ] ) ) {
            			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountDevices.AccountId = " . $_GET[ 'AccountId' ] . " )";
            		}
        			
                    $ct = 1; 
                    $query1 ="SELECT 
                        AccountDevices.AccountId AS AccountId,
                        AccountDevices.ServiceId AS ServiceId,
                        AccountDevices.TariffId AS TariffId,
                        AccountDevices.DeviceId AS DeviceId,
                        AccountDevices.Date AS AcDate, 
                        AccountDevices.Using AS AcUsing,
                        Devices.Name AS DevName,
                        Services.Name AS SerName,
                        Services.CompanyId AS CompanyId,
                        ManagementCompany.Name AS ManName,
                        PersonalAccounts.Name AS PersName,
                        TariffTypes.Name AS TarName
                        FROM AccountDevices AS AccountDevices
                        LEFT JOIN Devices AS Devices ON (AccountDevices.DeviceId = Devices.DeviceId)
                        LEFT JOIN Services AS Services ON (AccountDevices.ServiceId = Services.ServiceId)
                        LEFT JOIN PersonalAccounts AS PersonalAccounts ON (AccountDevices.AccountId = PersonalAccounts.AccountId)
                        LEFT JOIN TariffTypes AS TariffTypes ON (AccountDevices.TariffId = TariffTypes.TariffId )
                        LEFT JOIN ManagementCompany AS ManagementCompany ON (ManagementCompany.CompanyId = Services.CompanyId)
                        " . $where . "
                        ORDER BY PersName, SerName, TarName";
                        
                        //print_r($query1);


                    $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 


                    $fn = array("Лицевой счет","Коммунальная услуга","Вид тарифа","Прибор учета","Дата начала действия","Используется");


                    foreach($fn as $nm)
                        {
                            echo "<th>".$nm."</th>";
                        }
                    //echo "<th width='20px'><a href='./create_update_forms/form_users.php?id=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></th>";
                ?>
    		</tr>
    	<?php
    	       if($result1)
                {   
                while($row1 = mysqli_fetch_array($result1)){
                    echo "<tr id='tr_parent_".$ct."'>";
                    $ct = $ct + 1;
                    echo "<td>".$row1['PersName']."</td>";
                    echo "<td>" . $row1[ 'SerName' ] . ( empty( $row1[ 'ManName' ] ) ? "" : " (" . $row1[ 'ManName' ] . ")" ) . "</td>";
                   
                    echo "<td>".$row1['TarName']."</td>";
                    echo "<td>".$row1['DevName']."</td>";
                    
                    $date = strtotime($row1['AcDate']);			
				    echo "<td>".date('d.m.Y H:i:s',$date)."</td>";
                    
                    echo "<td>" . ( ( $row1['AcUsing'] == 1 ) ? "✅" : "" ) . "</td>";
                   

                    
                    $data[] = [     'AccountId'    => $row1[ 'AccountId' ],
						            'ServiceId'    => $row1[ 'ServiceId' ],
						            'TariffId'     => $row1[ 'TariffId' ],
						            'DeviceId'     => $row1[ 'DeviceId' ],
						            'Data'         => $row1['AcDate']];
                   
                    echo"</tr>";
                }
            }  
    	?>
    	</tbody>
    </table>
</div>
 <div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_accountdevices.php?AccountId=-1&ServiceId=-1&TariffId=-1&DeviceId=-1&Data=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_accountdevices.php?AccountId=" . $value[ 'AccountId' ] . "&ServiceId=".$value[ 'ServiceId' ]."&TariffId=".$value[ 'TariffId' ]."&DeviceId=".$value[ 'DeviceId' ]."&Data=".$value[ 'Data' ]."'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex3' onclick=\"DeleteAccountDevices('AccountDevices', " . $value[ 'AccountId' ] . ",".$value[ 'ServiceId' ].",".$value[ 'TariffId' ].",".$value[ 'DeviceId' ].", '".$value[ 'Data' ]."')\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
				//	echo "<td width='20px'><a href='#ex1' onclick=\"Delete('AccountDevices', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modalAccountDevices.php';
		?>
	</table>
</div>
<script>
    
    function DeleteAccountDevices(table, elem, elem2, elem3,elem4,elem5) {
        $('#DevAccountId').val(elem);
        $('#popup_table').val(table);
        $('#serviceId').val(elem2);
        $('#tariffId').val(elem3);
        $('#deviceId').val(elem4);
        $('#dateId').val(elem5);
       
}
</script>


