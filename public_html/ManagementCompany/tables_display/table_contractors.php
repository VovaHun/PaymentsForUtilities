<div class="wrapper_left">
    <table id="data_table">
        <tbody>
            <tr id='tr_parent_0'>
    	<?php
    	    $where = "WHERE ManagementCompany.CompanyId=".$company_admin['CompanyId'];
			
			if ( isset( $_GET[ 'RegionId' ] ) ) {
				$where =$where.( empty( $where ) ? "WHERE " : " AND " ) . "( Contractors.RegionId = " . $_GET[ 'RegionId' ] . " )";
			}
                      
            $ct=1;
    		// вывод данных таблицы
    		$query1 ="SELECT 
                        Contractors.СontractorId AS ContractorId,
                        Contractors.Name AS Name,
                        Contractors.LegalAddress AS LegalAddress,
                        Contractors.ActualAddress AS ActualAddress,
                        IFNULL (Contractors.EMail , 'Не указано') AS EMail,
                        IFNULL (Contractors.Phone , 'Не указано') AS Phone,
                        IFNULL (Contractors.INN , 'Не указано') AS INN,
                        IFNULL (Contractors.KPP , 'Не указано') AS KPP,
                        IFNULL (Contractors.OGRN , 'Не указано') AS OGRN,
                        IFNULL (Contractors.PositionHead, 'Не указано') AS PositionHead,
                        IFNULL (Contractors.FIO, 'Не указано') AS FIO,
                        Contractors.PaymentAccount AS PaymentAccount,
                        Contractors.Bank AS Bank,
                        Contractors.BIK AS BIK,
                        Contractors.CorrespondentAccount AS CorrespondentAccount,
                        Contractors.RegionId AS RegionId,
                        Regions.Name AS RegionName,
                        Regions.RegionСode AS RegionСode,
                        Regions.RegionId AS reRegionId,
                        ManagementCompany.RegionId AS ManRagionId
                        FROM  Contractors AS Contractors
                        LEFT JOIN Regions AS Regions ON (Regions.RegionId = Contractors.RegionId)
                        LEFT JOIN ManagementCompany AS ManagementCompany ON (Regions.RegionId = ManagementCompany.RegionId)
                        " . $where . "
                        ORDER BY Name";

    		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    
    		$fn =  array("ID","Наименование", "Юридический адрес", "Фактический адрес", "Электронная почта", "Телефон(ы)", "ИНН", "КПП", "ОГРН", "Должность руководителя","ФИО руководителя","Расчетный счет","Банк","БИК","Корр. счет","Регион");
    
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
                    $ct=$ct+1;
                    
                    echo "<td>".$row1['ContractorId']."</td>";
    				echo "<td>".$row1['Name']."</td>";
                    echo "<td>".$row1['LegalAddress']."</td>";
                    echo "<td>".$row1['ActualAddress']."</td>";
                    echo "<td>".$row1['EMail']."</td>";
                    echo "<td>".$row1['Phone']."</td>";
                    echo "<td>".$row1['INN']."</td>";
                    echo "<td>".$row1['KPP']."</td>";
                    echo "<td>".$row1['OGRN']."</td>";
                    echo "<td>".$row1['PositionHead']."</td>";
                    echo "<td>".$row1['FIO']."</td>";
                    echo "<td>".$row1['PaymentAccount']."</td>";
                    echo "<td>".$row1['Bank']."</td>";
                    echo "<td>".$row1['BIK']."</td>";
    				echo "<td>".$row1['CorrespondentAccount']."</td>";
                    echo "<td>".$row1['RegionСode'].", ".$row1['RegionName']."</td>";
    				
    				echo "</tr>"; 
    				
    				$data[] = $row1['ContractorId'];
    
    			}
    		}
    	?>
    	</tbody>
    </table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_contractors.php?ContractorId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_contractors.php?ContractorId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>





