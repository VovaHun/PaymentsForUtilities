<?php
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
?>

<body>
	<?php
    	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    	
		$deviceId = $_GET["DeviceId"];
		$date = $_GET["Date"];
		$fn = $names_ar[ 'DeviceIndications' ];
		$row1 = array(  
							  'DeviceId'                 => '',
							  'Date'                     => date( 'Y-m-d H:i:s' ),
							  'Indications'              => '',
							  'Fixed'                    => '0');
	
        if($deviceId != -1){
        $query1 ="SELECT 
						DeviceIndications.DeviceId AS DeviceId,
						DeviceIndications.Date AS 'Date',
						DeviceIndications.Indications AS Indications, 
						DeviceIndications.Fixed AS Fixed
						FROM DeviceIndications AS DeviceIndications
						WHERE 
						 DeviceIndications.DeviceId=".$deviceId." 
						AND DeviceIndications.Date ='".$date."' ";
     
		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
		
		if ( !$row1 = mysqli_fetch_array( $result1 ) ) {
				$row1 = array(  
							  'DeviceId'                 => '',
							  'Date'                     => date( 'Y-m-d H:i:s' ),
							  'Indications'              => '',
							  'Fixed'                    => '0');
        	}
        }
        
        
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Показания приборов учета</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_deviceindications.php">
 							<input type="hidden" name="elem_id" value='<?php echo $deviceId ?>'>
 							<input type="hidden" name="elem_date" value='<?php echo $date ?>'>
 							<div class="form_wrapper">
								<table>
								
									<tbody>
									<tr> 
										<td><label>Прибор учета: </label> </td>
										<td><select class='select_db' name="device"><?php
										echo "<option value = 'NULL'>Не указано</option>";
										$query = "SELECT * FROM `Devices` WHERE Devices.CompanyId = ".$company_admin['CompanyId'];
										$result_sel = mysqli_query($link, $query);
										
										while ($row = mysqli_fetch_array($result_sel)) 
										{	

											if($row['DeviceId']==$row1['DeviceId']){
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
								<td><label>Дата события: </label> </td>
									<td>
											<?php
											if($deviceId == -1)
												{ echo "<input type=datetime-local id=start name=date 
											value='".date( 'Y-m-d\TH:i:s', strtotime( $row1['Date'] ) )."' ></td>";  
    											}
    										else
    											{
    										echo "<input type=datetime-local id=start name=date 
    										value='" . date( 'Y-m-d\TH:i:s', strtotime( $row1['Date'] ) ) . "' required></td>";  
    										 
    											}
                    							?>
    										
                                        </td>
									</tr>
									<tr> 
										<td><label>Показания прибора учета: </label> </td>
										<td>
										<?php       
											if($deviceId == -1)
												{ 
													echo "<input type=text pattern='^[ 0-9]+(\.\d{2})?' placeholder=' 0.00' name=deviceIn value='' required>";  
    											}
    										else
    											{
    												echo "<input type=text pattern='^[ 0-9]+(\.\d{2})?' placeholder=' 0.00' name=deviceIn value='".$row1['Indications']."' required>";  
    											}
                                                
										?>
									</tr>
									<tr> 
										<td><label>Зафиксировано: </label> </td>
										<td>
										<?php 
										    echo "<input type=checkbox " . ( ( $row1[ 'Fixed' ] == 1 ) ? "checked " : "" ) . "name='fixed' id='fixed'>";
										    
											

										?>
										</td>
									</tr>
									</tbody>

								</table>
							</div>
							<input type="hidden" name="elem_table" value="DeviceIndications">
							<input type="submit" class="confirm" value="Сохранить">
							<?php 
								echo "<a href='/ManagementCompany/?table=DeviceIndications'>Отменить</a>";
							  ?>
							

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

