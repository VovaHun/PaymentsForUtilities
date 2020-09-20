<div class="wrapper_left" style="width: 100%">
    <table id="data_table">
        <tbody>
            <tr id='tr_parent_0'>
        <?php
            $where = "";
			
			if ( isset( $_GET[ 'RegionId' ] ) ) {
				$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( ManagementCompany.RegionId = " . $_GET[ 'RegionId' ] . " )";
			}

            $ct=1;
            // вывод данных таблицы
            $query1 ="SELECT  
                        ManagementCompany.CompanyId AS CompanyId, 
                        ManagementCompany.Name AS Name, 
                        ManagementCompany.FullName AS FullName,
                        ManagementCompany.LegalAddress AS LegalAddress,
                        ManagementCompany.ActualAddress AS ActualAddress, 
                        ManagementCompany.EMail AS EMail, 
                        ManagementCompany.Phone AS Phone,
                        ManagementCompany.INN AS INN,
                        ManagementCompany.KPP AS KPP,
                        ManagementCompany.OGRN AS OGRN,
                        ManagementCompany.PositionHead AS PositionHead,
                        ManagementCompany.FIO AS FIO,
                        ManagementCompany.PaymentAccount AS PaymentAccount,
                        ManagementCompany.Bank AS Bank, 
                        ManagementCompany.BIK AS BIK,  
                        ManagementCompany.CorrespondentAccount AS CorrespondentAccount, 
                        Regions.Name AS RegionName,
                        Regions.RegionСode AS RegionСode,
                        ManagementCompany.CompanyId AS CompanyId
                      FROM ManagementCompany 
                      LEFT JOIN Regions AS Regions ON (ManagementCompany.RegionId = Regions.RegionId )
                      " . $where . "
                    ORDER BY Name";

            $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    
            
    
            $fn = array("ID","Наименование управляющей компании ","Полное наименование"," Юридический адрес","Фактический адрес","Электронная почта","Телефон(ы)","ИНН","КПП","ОГРН","Должность руководителя","ФИО руководителя","Расчетный счет","Банк","БИК","Корр. счет","Регион");
    
                    foreach($fn as $nm){
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
                    echo "<td>".$row1['CompanyId']."</td>";
                    echo "<td>".$row1['Name']."</td>";
                    echo "<td>".$row1['FullName']."</td>";
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

                    $data[] = $row1['CompanyId'];
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
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_managementcompany.php?CompanyId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_managementcompany.php?CompanyId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('ManagementCompany', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>