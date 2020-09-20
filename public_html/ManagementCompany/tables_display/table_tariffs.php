<div class="wrapper_left">
<table id="data_table">
	<?php
	    $where = "WHERE Tariffs.CompanyId=".$company_admin['CompanyId'];
			

		if ( isset( $_GET[ 'TariffId' ] ) ) {
			$where = $where.( empty( $where ) ? "WHERE " : " AND " ) . "( Tariffs.TariffId = " . $_GET[ 'TariffId' ] . " )";
		}
	
		if ( isset( $_GET[ 'RegionId' ] ) ) {
			$where = $where.( empty( $where ) ? "WHERE " : " AND " ) . "( Tariffs.RegionId = " . $_GET[ 'RegionId' ] . " )";
		}
        
        if ( isset( $_GET[ 'ServiceId' ] ) ) {
			$where = $where.( empty( $where ) ? "WHERE " : " AND " ) . "( Tariffs.ServiceId = " . $_GET[ 'ServiceId' ] . " )";
		}
		
		if ( isset( $_GET[ 'CompanyId' ] ) ) {
			$where = $where.( empty( $where ) ? "WHERE " : " AND " ) . "( Tariffs.CompanyId = " . $_GET[ 'CompanyId' ] . " )";
		}

	    $ct=1;
		// вывод данных таблицы
		$query1 ="SELECT 
					Tariffs.TariffId AS TariffId,
					Tariffs.ServiceId AS ServiceId,
					Tariffs.RegionId AS RegionId,
					Tariffs.Date AS 'Date',
					Tariffs.Price AS Price,
					Tariffs.CompanyId AS CompanyId,
					TariffTypes.Name AS TypeName,
					Services.Name AS SerName,
					Services.СontractorId AS СontractorId,
                    Contractors.Name AS ContName,
					Regions.Name AS RegionName,
					Regions.RegionСode AS RegionСode,
					ManagementCompany.Name AS CompanyName,
					ManagementCompany.RegionId AS ManReginoId
					FROM Tariffs
					LEFT JOIN TariffTypes AS TariffTypes ON (TariffTypes.TariffId = Tariffs.TariffId)
					LEFT JOIN Services AS Services ON (Services.ServiceId = Tariffs.ServiceId)
					LEFT JOIN Regions AS Regions ON (Regions.RegionId=Tariffs.RegionId)
					LEFT JOIN ManagementCompany AS ManagementCompany ON (ManagementCompany.CompanyId = Tariffs.CompanyId )
					LEFT JOIN Contractors AS Contractors ON (Contractors.СontractorId = Services.СontractorId)
					" . $where . "
                    ORDER BY CompanyId, TypeName, Date";

      

		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 

		$num_columns = $result1->field_count;

		$fn = $names_ar['Tariffs'];

		$query3 ="SHOW COLUMNS FROM `Tariffs`";
		$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link)); 

		if($result3)
		{
		    $row3 = mysqli_fetch_array($result3);		    
		}
		$ct = 1;
	?>
	<!--Вывод названий столбцов в таблице   -->
	<tbody>
		<tr id='tr_parent_0'>
			<?php
				foreach($fn as $nm)
					{
					    echo "<th>".$nm."</th>";
					}
				?>						
		</tr>

	<?php
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		

		if($result1)
		{
		    while($row1 = mysqli_fetch_array($result1)){
		    	echo "<tr id='tr_parent_".$ct."'>";
		    	$ct = $ct + 1;

		    	echo "<td>".$row1['TypeName']."</td>"; 
		        echo "<td>" . $row1[ 'SerName' ] . ( empty( $row1[ 'CompanyName' ] ) ? "" : " (" . $row1[ 'CompanyName' ] . ")" ) . "</td>";
			//	echo "<td>".$row1['SerName']."</td>"; 

				echo "<td>".$row1['RegionСode'].", ".$row1['RegionName']."</td>";
                echo "<td>".$row1['CompanyName']."</td>";
				$date = strtotime($row1['Date']);			
				echo "<td>".date('d.m.Y',$date)."</td>"; 
				echo "<td>".$row1['Price']."</td>";
				
			    echo "</tr>";
			    
			    $data[] = ['TariffId' => $row1['TariffId'], 
			               'ServiceId' =>  $row1['ServiceId'],
			               'RegionId' => $row1['RegionId']];
			}
		}
	?>
	</tbody>
</table>
</div>

<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_tariffs.php?TariffId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;
				foreach ( $data as $value ) {
				    echo "<tr id='tr_child_" .$ct. "'>";
				    
				    echo "<td width='20px'><a href='./create_update_forms/form_tariffs.php?TariffId=" . $value['TariffId'] . "&ServiceId=" . $value['ServiceId'] . "&RegionId=" . $value['RegionId'] . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
				   
				    $ct = $ct + 1;
				    echo "</tr>";
				}
			}
			
		?>
	</table>
</div>


