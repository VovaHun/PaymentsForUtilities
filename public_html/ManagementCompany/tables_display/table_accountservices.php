<div class="wrapper_left">
    <table id="data_table">
    	<tbody>
    		<tr id='tr_parent_0'>
                <?php
                    $where = "WHERE PersonalAccounts.CompanyId =".$company_admin['CompanyId'];
			
        			if ( isset( $_GET[ 'TariffId' ] ) ) {
        				$where =$where.( empty( $where ) ? "WHERE " : " AND " ) . "( AccountServices.TariffId = " . $_GET[ 'TariffId' ] . " )";
        			}
        			
            		
            		if ( isset( $_GET[ 'ServiceId' ] ) ) {
            			$where =$where.( empty( $where ) ? "WHERE " : " AND " ) . "( AccountServices.ServiceId = " . $_GET[ 'ServiceId' ] . " )";
            		}
            		
            		if ( isset( $_GET[ 'AccountId' ] ) ) {
            			$where =$where.( empty( $where ) ? "WHERE " : " AND " ) . "( AccountServices.AccountId = " . $_GET[ 'AccountId' ] . " )";
            		}

                    $ct = 1; 
                    $query1 ="SELECT 
                        PersonalAccounts.Name AS PersName, 
                        PersonalAccounts.CompanyId AS PersCompanyId,
                        Services.Name AS SerName, 
                        Services.CompanyId AS CompanyId,
                        ManagementCompany.Name AS ManName,
                        AccountServices.TariffId AS TariffId, 
                        AccountServices.AccountId AS AccountId,
                        AccountServices.ServiceId AS ServiceId ,
                        AccountServices.CalculationType AS CalculationType,
                        AccountServices.Date AS AcSerDate, 
                        AccountServices.Using AS AcSerUsing, 
                        IFNULL (AccountServices.Portion,1) AS Portion, 
                        IFNULL (AccountServices.Сoefficient,0) AS Coefficient,  
                        TariffTypes.TariffId AS TarId, 
                        TariffTypes.Name AS TarName
                    FROM AccountServices
                    LEFT JOIN Services AS Services ON (AccountServices.ServiceId = Services.ServiceId)
                    LEFT JOIN PersonalAccounts AS PersonalAccounts ON (AccountServices.AccountId = PersonalAccounts.AccountId)
                    LEFT JOIN TariffTypes AS TariffTypes ON (AccountServices.TariffId = TariffTypes.TariffId )
                    LEFT JOIN ManagementCompany AS ManagementCompany ON (ManagementCompany.CompanyId = Services.CompanyId)
                    " . $where . "
                    ORDER BY PersName, SerName, TarName";
                    

                    $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 

                    $fn = array("Лицевой счет","Коммунальная услуга","Вид тарифа","Вид расчета","Дата начала действия","Используется","Доля","Дополнительный коэффициент");
    
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
    				
    				echo "<td>".$row1['PersName']."</td>";
    				
    				echo "<td>" . $row1[ 'SerName' ] . ( empty( $row1[ 'ManName' ] ) ? "" : " (" . $row1[ 'ManName' ] . ")" ) . "</td>";
    				
                    //echo "<td>".$row1['SerName']."</td>";
                    echo "<td>".$row1['TarName']."</td>";
                    echo "<td>" . ( ( $row1['CalculationType'] == 1 ) ? "По нормативам" : 
                        ( ( $row1['CalculationType'] == 2 ) ? "По показаниям прибора" : 
                        ( ( $row1['CalculationType'] == 3) ? "Фиксированной суммой" : 
                        ( ( $row1['CalculationType'] == 4) ? "Пропорционально площади объектов" :
                        ( ( $row1['CalculationType'] == 5) ? " Пропорционально показаниям индивидуальных и общедомовых приборов учета" : "")) ) ) ). "</td>";

                    $date = strtotime($row1['AcSerDate']);            
                    echo "<td>".date('d.m.Y H:i:s',$date)."</td>";
                    
                    echo "<td>" . ( ( $row1['AcSerUsing'] == 1 ) ? "✅" : "" ) . "</td>";
                    
                
                    echo "<td>".$row1['Portion']."</td>";
                    echo "<td>".number_format($row1['Coefficient'],2,'.','')."</td>";
    				echo"</tr>";
    				
                  	
                    $data[]=[ 'AccountId' =>    $row1['AccountId'],
                              'ServiceId' =>   $row1['ServiceId'] ,
                               'TariffId' =>  $row1['TariffId'],
                               'Date' => $row1['AcSerDate']];
    			}

    		}
    	?>
    	</tbody>
    </table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_accountservices.php?AccountId=-1&ServiceId=-1&TariffId=-1&Date=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
						echo "<td width='20px'><a href='./create_update_forms/form_accountservices.php?AccountId=" . $value['AccountId'] . "&ServiceId=" . $value['ServiceId'] . "&TariffId=" . $value['TariffId'] . "&Date=" . $value['Date'] . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			
		?>
	</table>
</div>







