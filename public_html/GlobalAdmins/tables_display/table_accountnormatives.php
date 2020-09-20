<div class="wrapper_left">
    <table id="data_table">
    	<tbody>
    		<tr id='tr_parent_0'>
                <?php
                    $where = "";
			
        			if ( isset( $_GET[ 'TariffId' ] ) ) {
        				$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountNormatives.TariffId = " . $_GET[ 'TariffId' ] . " )";
        			}
            		
            		if ( isset( $_GET[ 'ServiceId' ] ) ) {
            			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountNormatives.ServiceId = " . $_GET[ 'ServiceId' ] . " )";
            		}
            		
            		if ( isset( $_GET[ 'AccountId' ] ) ) {
            			$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( AccountNormatives.AccountId = " . $_GET[ 'AccountId' ] . " )";
            		}

                    $ct = 1; 
                    $query1 ="SELECT 
                        PersonalAccounts.Name AS PersName, 
                        Services.Name AS SerName,
                        Services.CompanyId AS CompanyId,
                        ManagementCompany.Name AS ManName,
                        TariffTypes.Name AS TarName, 
                        AccountNormatives.Date AS AcNormDate, 
                        AccountNormatives.Count AS AcNormCount, 
                        AccountNormatives.Using AS AcNormUsing,
                        AccountNormatives.AccountId AS AccountId,
                        AccountNormatives.ServiceId AS ServiceId,
                        AccountNormatives.TariffId AS TariffId
                        FROM AccountNormatives AS AccountNormatives 
                        LEFT JOIN Services AS Services ON (AccountNormatives.ServiceId = Services.ServiceId)
                        LEFT JOIN PersonalAccounts AS PersonalAccounts ON (AccountNormatives.AccountId = PersonalAccounts.AccountId)
                        LEFT JOIN TariffTypes AS TariffTypes ON (AccountNormatives.TariffId = TariffTypes.TariffId )
                        LEFT JOIN ManagementCompany AS ManagementCompany ON (ManagementCompany.CompanyId = Services.CompanyId)
                        " . $where . "
                        ORDER BY PersName, SerName, TarName";
                        
                    $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

                    $fn = array("Лицевой счет","Коммунальная услуга","Вид тарифа","Дата начала действия","Показатель расчета по нормативам","Используется");

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
    				echo "<td>".$row1['PersName'] ."</td>";
    				echo "<td>" . $row1[ 'SerName' ] . ( empty( $row1[ 'ManName' ] ) ? "" : " (" . $row1[ 'ManName' ] . ")" ) . "</td>";
                   
                    echo "<td>".$row1['TarName'] ."</td>";
                    $date = strtotime($row1['AcNormDate']);            
                    echo "<td>".date( 'd.m.Y H:i:s', $date) ."</td>";
                    echo "<td>".$row1['AcNormCount'] ."</td>";
                    echo "<td>" . ( ( $row1['AcNormUsing'] == 1 ) ? "✅" : "" ) . "</td>";
                    
    				echo"</tr>";
    				
    				$data[]=[ 'AccountId' =>    $row1['AccountId'],
                              'ServiceId' =>   $row1['ServiceId'] ,
                               'TariffId' =>  $row1['TariffId'],
                               'Date' => $row1['AcNormDate']];
    			}
    		}
    	?>
    	</tbody>
    </table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_accountnormatives.php?AccountId=-1&ServiceId=-1&TariffId=-1&Date=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_accountnormatives.php?AccountId=" . $value['AccountId'] . "&ServiceId=" . $value['ServiceId'] . "&TariffId=" . $value['TariffId'] . "&Date=" . $value['Date'] . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex3' onclick=\"DeleteAccountNorm('AccountNormatives', " . $value[ 'AccountId' ] . ",".$value[ 'ServiceId' ].",".$value[ 'TariffId' ].",'".$value['Date']."')\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modalAccountNormativs.php';
		?>
	</table>
</div>
<script>
    
    function DeleteAccountNorm(table, elem, elem2, elem3, elem4) {
        $('#normAccountId').val(elem);
        $('#norm_table').val(table);
        $('#serviceId').val(elem2);
        $('#tariffId').val(elem3);
        $('#dateId').val(elem4);
        
}
</script>





