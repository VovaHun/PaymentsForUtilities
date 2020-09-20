<div class="wrapper_left">
<table id="data_table">
	<tbody>
		<tr id='tr_parent_0'>
	<?php
	    $where = "WHERE Services.CompanyId = ".$company_admin['CompanyId'];
			
			if ( isset( $_GET[ 'UnitId' ] ) ) {
				$where = $where . ( empty( $where ) ? "WHERE " : " AND " ) . "( Services.UnitId = " . $_GET[ 'UnitId' ] . " )";
			}
			if ( isset( $_GET[ 'СontractorId' ] ) ) {
				$where = $where . ( empty( $where ) ? "WHERE " : " AND " ) . "( Services.СontractorId = " . $_GET[ 'СontractorId' ] . " )";
			}
			
			if ( isset( $_GET[ 'CompanyId' ] ) ) {
				$where = $where . ( empty( $where ) ? "WHERE " : " AND " ) . "( Services.CompanyId = " . $_GET[ 'CompanyId' ] . " )";
			}
			
			if ( isset( $_GET[ 'MainServiceId' ] ) ) {
				$where = $where . ( empty( $where ) ? "WHERE " : " AND " ) . "( Services.MainServiceId = " . $_GET[ 'MainServiceId' ] . " )";
			}

		$ct=1;
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		// вывод данных таблицы
				$query1 ="SELECT 
							Services.ServiceId AS ServiceId,
							Services.Name AS Name,
							Services.СontractorId AS СontractorId,
							Services.CompanyId AS CompanyId,
							Services.IsComposite AS IsComposite,
							IFNULL(Services.MainServiceId,0) AS MainServiceId,
							Services.UnitId AS UnitId,
							Services.IsPrint AS IsPrint,
							Contractors.Name AS ContName, 
							Units.Name AS UnitsName,
							IFNULL(ManagementCompany.Name,'') AS CompanyName
							FROM Services AS Services 
							LEFT JOIN Contractors AS Contractors ON (Contractors.СontractorId = Services.СontractorId)
							LEFT JOIN Units AS Units ON (Units.UnitId =  Services.UnitId ) 
							LEFT JOIN ManagementCompany AS ManagementCompany ON (ManagementCompany.CompanyId =  Services.CompanyId ) 
							" . $where . "
                            ORDER BY CompanyName, Name";

		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

		$fn = array("ID","Наименование", "Поставщик коммунальной услуги","Управляющая компания","Составная ","Входит в состав услуги","Единица измерения","Печать в платежках");

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

		    	 /* ID комунальной услуги*/
		    	echo "<td>".$row1['ServiceId']."</td>";
				echo "<td>".$row1['Name']."</td>"; /* Наименование*/
				echo "<td>".$row1['ContName']."</td>"; /* Поставщик ком услуги*/
				echo "<td>".$row1['CompanyName']."</td>";
				echo "<td>" . ( ( $row1[ 'IsComposite' ] == 1 ) ? "✅" : "" ) . "</td>";
			
				// Выбор родительской услуги от поля IsComposite
				$query2= "SELECT Services.ServiceId, Services.Name FROM Services WHERE Services.ServiceId =".$row1['MainServiceId'];
		        $result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link));
                 $row2 = mysqli_fetch_array($result2);
				echo "<td>" . ( ($row1['MainServiceId'] == 0 ) ? "Не указано" : $row2[1] ) . "</td>";
				
				echo "<td>".$row1['UnitsName']."</td>";/*Ед. измерения*/
				echo "<td>" . ( ( $row1[ 'IsPrint' ] == 1 ) ? "✅" : "" ) . "</td>";

				echo "</tr>";
				
                $data[] = $row1['ServiceId'];
			}
		}
	?>
	</tbody>
</table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_services.php?ServiceId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_services.php?ServiceId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					//echo "<td width='20px'><a href='#ex1' onclick=\"Delete('Services', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>

