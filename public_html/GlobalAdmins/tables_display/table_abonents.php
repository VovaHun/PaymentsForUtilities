<div class="wrapper_left">
<table id="data_table" >
	<tbody id="left-body">
		<tr id='tr_parent_0'>
               <?php
					
					$ct = 1; 
					$fn = array( "Абонент","Тип абонента","ФИО","Юридический адрес или адрес регистрации","Фактический адрес или адрес проживания","Электронная почта","Телефон(ы)","	ИНН","КПП","ОГРН","Дата рождения","Должность руководителя","ФИО руководителя компании","Расчетный счет ","	Банк ","БИК","Корр. счет ","Согласие на обработку персональных данных");
				
                    foreach ($fn as $nm) {
						echo "<th>" .$nm. "</th>";
					}
					
                ?>
    						
		</tr>
		<!--Вывод названий столбцов в таблице   -->
	<?php
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		

		$query = "SELECT 
					Abonents.AbonentId AS AbonentId,
					Abonents.AbonentType AS AbonentType,
					Abonents.Name AS Name,
					Abonents.LegalAddress AS LegalAddress,
					Abonents.ActualAddress AS ActualAddress,
					Abonents.Email AS Email,
					Abonents.Phone AS Phone,
					Abonents.INN AS INN,
					Abonents.KPP AS KPP,
					IFNULL (Abonents.OGRN, '') AS OGRN,
				    Abonents.DateOfBirth AS DateOfBirth,
					IFNULL (Abonents.PositionHead, '') AS PositionHead,
					IFNULL (Abonents.FIO, '') AS FIO,
					IFNULL (Abonents.PaymentAccount, '') AS PaymentAccount,
					IFNULL (Abonents.Bank, '') AS Bank,
					IFNULL (Abonents.BIK, '')  AS BIK,
					IFNULL (Abonents.CorrespondentAccount, '') AS CorrespondentAccount,
					IFNULL (Abonents.ConcentOnPersonalData, '') AS ConcentOnPersonalData
					FROM Abonents 
					ORDER BY Name";

		$result1 = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
		

		

		if($result1)
		{

		    while($row1 = mysqli_fetch_array($result1))
		    {   	
		    	echo "<tr id='tr_parent_".$ct."'>";
		    	$ct = $ct + 1;

		    	echo  "<td>".$row1['AbonentId']."</td>";
		    	echo "<td>" . ( ( $row1[ 'AbonentType' ] == 1 ) ? "Фиическое лицо" : ( ( $row1[ 'AbonentType' ] == 2 ) ? "Юридическое лицо" : "" ) ) . "</td>";		 		 
		    	echo  "<td>".$row1['Name']."</td>";
		    	echo  "<td>".$row1['LegalAddress']."</td>";
		    	echo  "<td>".$row1['ActualAddress']."</td>";
		    	echo  "<td>".$row1['Email']."</td>";
		    	echo  "<td>".$row1['Phone']."</td>";
		    	echo  "<td>".$row1['INN']."</td>";
		    	echo  "<td>".$row1['KPP']."</td>";
		    	echo  "<td>".$row1['OGRN']."</td>";
		    	if(!empty($row1['DateOfBirth'])){
		    	    	$date = strtotime($row1['DateOfBirth']);
		    	        echo  "<td>".date('d.m.Y',$date)."</td>";
		    	}
		    	else {
		    	    echo  "<td>Не указано</td>";
		    	}
		    	echo  "<td>".$row1['PositionHead']."</td>";
		    	echo  "<td>".$row1['FIO']."</td>";
		    	echo  "<td>".$row1['PaymentAccount']."</td>";
		    	echo  "<td>".$row1['Bank']."</td>";
		    	echo  "<td>".$row1['BIK']."</td>";
		    	echo  "<td>".$row1['CorrespondentAccount']."</td>";
		    	echo "<td>" . ( ( $row1[ 'ConcentOnPersonalData' ] == 1 ) ? "✅" : "" ) . "</td>";


		    	//echo "<td width='20px' ><a href='./create_update_forms/form_abonents.php?id=" .$row1[ 'AbonentId' ]."'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
				//echo "<td width='20px' ><a href='#ex1' onclick=\"Delete('Abonents', " . $row1[ 'AbonentId' ] . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
		    
				$data[] = $row1[ 'AbonentId' ];
				echo"</tr>";
			}

			echo "</tbody>";
			
		}

	?>
	
</table>
</div>

<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_abonents.php?AbonentId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_abonents.php?AbonentId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "<td width='20px'><a href='#ex1' onclick=\"Delete('Abonents', " . $value . ")\" rel='modal:open'><img src='../images/Delete.png' alt='Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
