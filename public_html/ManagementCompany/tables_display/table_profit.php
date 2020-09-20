<div class="wrapper_left" style="width: 100%">
<table id="data_table">
	<tbody>
		<tr id='tr_parent_0'>
	<?php
	    $where = "WHERE PersonalAccounts.CompanyId = '".$company_admin['CompanyId']."'";
			
			/*if ( isset( $_GET[ 'UnitId' ] ) ) {
				$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( Services.UnitId = " . $_GET[ 'UnitId' ] . " )";
			}*/
			
		$ct=1;
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		// вывод данных таблицы
				$query1 ="SELECT 
							Profit.Period AS Period,
							Profit.StartDate AS StartDate,
							Profit.EndDate AS EndDate,
							Profit.AccountId AS AccountId,
							Profit.ServiceId AS ServiceId,
							
							Profit.TariffId AS TariffId,
							Profit.CalculationType AS CalculationType,
							Profit.Portion AS Portion,
							Profit.Сoefficient AS Сoefficient,
							Profit.NormativeCount AS NormativeCount,
							
							Profit.DeviceId AS DeviceId,
							Profit.PreviousIndications AS PreviousIndications,
							Profit.CurrentIndications AS CurrentIndications,
							Profit.Indications AS Indications,
							Profit.FixedSumma AS FixedSumma,
							
							Profit.ObjectId AS ObjectId,
							Profit.ObjectSquare AS ObjectSquare,
							Profit.ParentId AS ParentId,
							Profit.ParentIndications AS ParentIndications,
							Profit.TotalIndications AS TotalIndications,
							
							Profit.SharedIndications AS SharedIndications,
							Profit.TotalSquare AS TotalSquare,
							Profit.PortionSquare AS PortionSquare,
							Profit.IndividualIndications AS IndividualIndications,
							Profit.PortionIndications AS PortionIndications,
							
							Profit.Price AS Price,
							Profit.Summa AS Summa,
							
							IFNULL(PersonalAccounts.Name,'') AS PersName,
							IFNULL(Services.Name,'') AS ServName,
							IFNULL(TariffTypes.Name,'') AS TarName,
							IFNULL(Devices.Name,'') AS DevName,
							IFNULL(Objects.Name,'') AS ObjName,
							IFNULL(Parents.Name,'') AS ParentName

							FROM Profit AS Profit 
							
							LEFT JOIN PersonalAccounts AS PersonalAccounts ON (PersonalAccounts.AccountId = Profit.AccountId)
							LEFT JOIN Services AS Services ON (Services.ServiceId = Profit.ServiceId)
							LEFT JOIN TariffTypes AS TariffTypes ON (TariffTypes.TariffId = Profit.TariffId)
							LEFT JOIN Devices AS Devices ON (Devices.DeviceId = Profit.DeviceId)
							LEFT JOIN Objects AS Objects ON (Objects.ObjectId = Profit.ObjectId)
							LEFT JOIN Objects AS Parents ON (Parents.ObjectId = Objects.ParentId)
							" . $where . "
                            ORDER BY Period, PersName";

       

		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

		$fn = array("Период","Начальная дата", "Конечная дата","Лицевой счет","Коммунальная услуга", "Вид расчета", "Доля", "Доп. коэффициент", "Норматив", "Прибор учета", "Предыдущее показание", "Текущее показание", "Показания", "Фиксированная сумма", "Объект недвижимости", "Площадь объекта", "Родительский объект", "Показания ОДН", "Общие показания", "Показания к распределению", "Общая площадь", "Доля площади",  "Доля показаний", "Цена", "Сумма");

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
		    	$period = strtotime($row1['Period']);			
				echo "<td>".date('d.m.Y',$period)."</td>";
		    	
				$startdate = strtotime($row1['StartDate']);			
				echo "<td>".date('d.m.Y',$startdate)."</td>";
				
				$enddate = strtotime($row1['EndDate']);			
				echo "<td>".date('d.m.Y',$enddate)."</td>";
				
				echo "<td>".$row1['PersName']."</td>";
				echo "<td>".$row1['ServName']."</td>";
				//echo "<td>".$row1['TarName']."</td>";
				
				switch ($row1['CalculationType']) {
                	case '1':
                		echo "<td>По нормативам потребления</td>";
                		break;
                	case '2':
                		echo "<td>По показаниям приборов учёта</td>";
                		break;
                	case '3':
                		echo "<td>Фиксированной суммой</td>";
                		break;
                	case '4':
                		echo "<td>Пропорционально площадей объектов недвижимости</td>";
                		break;
                	case '5':
                		echo "<td>Пропорционально показаниям индивидуальных и общедомовых приборов учёта</td>";
                		break;
				}
				echo "<td>".number_format($row1['Portion'], 2, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".number_format($row1['Сoefficient'], 2, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".number_format($row1['NormativeCount'], 2, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".$row1['DevName']."</td>";
				echo "<td>".number_format($row1['PreviousIndications'], 3, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".number_format($row1['CurrentIndications'], 3, ',', $thousands_sep = '&nbsp;')."</td>";
                echo "<td>".number_format($row1['Indications'], 3, ',', $thousands_sep = '&nbsp;')."</td>";
                echo "<td>".number_format($row1['FixedSumma'], 2, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".$row1['ObjName']."</td>";
				echo "<td>".number_format($row1['ObjectSquare'], 2, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".$row1['ParentName']."</td>";
				echo "<td>".number_format($row1['ParentIndications'], 3, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".number_format($row1['TotalIndications'], 3, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".number_format($row1['SharedIndications'], 3, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".$row1['TotalSquare']."</td>";
				echo "<td>".$row1['PortionSquare']."</td>";
				//echo "<td>".$row1['IndividualIndications']."</td>";
				echo "<td>".$row1['PortionIndications']."</td>";
				echo "<td>".number_format($row1['Price'], 2, ',', $thousands_sep = '&nbsp;')."</td>";
				echo "<td>".number_format($row1['Summa'], 2, ',', $thousands_sep = '&nbsp;')."</td>";

				echo "</tr>";
				
                $data[] = ["AccountId" => $row1['AccountId'], 
                           "Period" => $row1['Period']];
			}
		}
	?>
	</tbody>
</table>
<div class="wrapper_button">
    <a id="profit_button" href='./create_update_forms/form_profit.php' class="confirm">Рассчитать</a>
</div>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;
				$account_id = null;
				$period = null;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					if ( ( $account_id != $value['AccountId'] ) || ( $period != $value['Period'] ) ) {
						$account_id = $value['AccountId'];
						$period = $value['Period'];
						
						echo "<td width='20px'><a href='./create_update_forms/print_profit.php?AccountId=".$value['AccountId']."&Period=".$value['Period']."'><img width='20px' height='20px' src='../images/print_black.png' alt='Отобразить документ'></a></td>";
					}
					else {
						echo "<td width='20px'>&nbsp;</a></td>";
					}
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			//require_once '../includes/modal.php';
		?>
	</table>
</div>