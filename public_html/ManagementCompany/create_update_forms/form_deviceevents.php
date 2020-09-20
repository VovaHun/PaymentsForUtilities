<?php
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
?>

<body>
	<?php
    	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    	$deviceId = $_GET["DeviceId"];
    	$eventType = $_GET["EventType"];
    	$eventDate = $_GET["EventDate"];
    	
    	$fn  = $names_ar[ 'DeviceEvents' ];
    	
    	$row1 = array(         
							  'DeviceEventsId'                 => '',
							  'EventType'                => '',
							  'EventDate'                => date( 'Y-m-d H:i:s' ),
							  'Indications'              => '',
							  'Using'                    => '0');
    	
    	if( $deviceId != -1){
    	    	$query1 ="SELECT 
						
						DeviceEvents.DeviceId AS DeviceEventsId,
						DeviceEvents.EventType AS EventType,
						DeviceEvents.EventDate AS EventDate,
						DeviceEvents.Indications AS Indications,
						DeviceEvents.Using AS 'Using'
						FROM DeviceEvents AS DeviceEvents WHERE DeviceEvents.DeviceId =".$deviceId."
						AND DeviceEvents.EventType=".$eventType." 
						AND DeviceEvents.EventDate ='".$eventDate."' ";
        
		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
		if ( !$row1 = mysqli_fetch_array( $result1 ) ) {
				$row1 = array( 
							  'DeviceEventsId'                 => '',
							  'EventType'                => '',
							  'EventDate'                => date( 'Y-m-d H:i:s' ),
							  'Indications'              => '',
							  'Using'                    => '0');
        	}
    	    
    	}
		//print_r($row1);
		$yesNo = array("Нет", "Да");
		$typeName = array("Установка","Демонтаж","Отключение","Начало проверки","Окончание проверки");													
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>События приборов учета</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_deviceevents.php">
 							<input type="hidden" name="elem_id" value='<?php echo $deviceId ?>'>
 							<input type="hidden" name="elem_type" value='<?php echo $eventType ?>'>
 							<input type="hidden" name="elem_date" value='<?php echo $eventDate ?>'>
 							<div class="form_wrapper">
								<table>
								
									<tbody>
									<tr> 
										<td><label>Прибор учета: </label> </td>
										<td><select class='select_db' name="device"><?php
										
										$query = "SELECT * FROM `Devices` WHERE Devices.CompanyId = ".$company_admin['CompanyId'];
										$result_sel = mysqli_query($link, $query);
										
										while ($row = mysqli_fetch_array($result_sel)) 
										{	

											if($row['DeviceId']==$row1["DeviceEventsId"]){
												echo "<option selected value = ".$row['DeviceId'].">".$row['Name']." </option> ";
												
											}
											else{
												echo "<option value = ".$row['DeviceId'].">".$row['Name']." </option> ";
											
											}								
										} 																				

										?>

								    </select>
								    </td>
									</tr>
									<tr> 
										<td><label>Тип события: </label> </td>
										<td><select class='select_db' name="type">
											<?php
											    
												for($i=0;$i<count($typeName);$i++){
													if($i+1==$row1['EventType']){
														
														echo "<option selected value = ".$i.">".$typeName[$i]." </option> ";
													}
													else{
														
														echo "<option value = ".$i.">".$typeName[$i]." </option> ";
													}														
												}
											?>
										</select></td>
									</tr>
									<tr> 
										<td><label>Дата события: </label> </td>
										<td>
											<?php
											if($deviceId  == -1)
												{ 
												    echo "<input type=datetime-local name='date' id='start' value='" . date( 'Y-m-d\TH:i:s', strtotime( $row1[ 'EventDate' ] ) ) . "' required>";  
    											}
    										else
    											{
    										        echo "<input type=datetime-local name='date' id='start' value='" . date( 'Y-m-d\TH:i:s', strtotime( $row1[ 'EventDate' ] ) ) . "' required>";  
    											}
    										?>

									</tr>
									<tr> 
										<td><label>Показания на дату события: </label> </td>
										<td>
										<?php       
											if($deviceId  == -1)
												{ 
													echo "<input type=text name=deviceIn value='' required>";  
    											}
    										else
    											{
    												echo "<input type=text name=deviceIn value='".$row1['Indications']."' required>";  
    											}

										?>
										</td>
									</tr>
									</tbody>

								</table>
							</div>
							<input type="hidden" name="elem_table" value="DeviceEvents">
							<input type="submit" class="confirm" value="Сохранить">
							<a href="/ManagementCompany/?table=DeviceEvents">Отменить</a>

						</form>

					</div>
			</div>
			
		</div>
	</div>

	<div class="footer">
		<div class="container"></div>
	</div>
</body>
</html>

