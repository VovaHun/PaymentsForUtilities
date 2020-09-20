<?php
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
?>

<body>
	<?php
	    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	    
		$res = $_GET["DeviceId"];
        $fn  = $names_ar[ 'Devices' ];
        if($res != - 1){
            $query1 ="SELECT 
					Devices.DeviceId AS DeviceId,
					Devices.Name AS Name,
					Devices.ReleaseDate AS ReleaseDate,
					Devices.StartIndications AS StartIndications,
					Devices.ModelId AS ModelId,
					Devices.NextDateCheck AS NextDateCheck, 
					Devices.CompanyId AS CompanyId 
					
		    		FROM Devices AS Devices WHERE Devices.DeviceId =".$res;
		    		
		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
             if ( !$row1 = mysqli_fetch_array( $result1 ) ) {
				$row1 = array( 'DeviceId'                => -1, 
							  'Name'                     => '',
							  'ReleaseDate'              => date( 'd.m.Y H:i:s' ),
							  'StartIndications'         => '',
							  'ModelId'                  => '',
							  'NextDateCheck'            =>date( 'd.m.Y H:i:s' ),
							  'CompanyId'                 => '');
							 
        	}
            
        }
		
		
															
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Приборы учета</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_devices.php">
 							<input type="hidden" name="elem_id" value='<?php echo $res?>'>
 							<div class="form_wrapper">
								<table>
								
									<tbody>
									<tr> 
										<td><label>Наименование прибора учета: </label> </td>
										<td>
										<?php       
										if($res == -1)
											{ 
												echo "<input type=text name=deviceName value='' required>";  
    										}
    									else
    										{
    											echo "<input type=text name=deviceName value='".$row1['Name']."' required>";  
    										}

										?>
								</td>
							</tr>
			
							<tr> 
								<td><label>Дата выпуска: </label> </td>
										<td>
											<?php
											if($res == -1)
												{ echo "<input type=date id=start name=date 
											value=date('d.m.Y')
    										 required></td>";  
    											}
    										else
    											{
    										echo "<input type=date id=start name=date 
    										value=".$row1['ReleaseDate']."
    										 required></td>";  
    											}
    										?>

							</tr>
							<tr> 
								<td><label>Начальные показания: </label> </td>
										<td>
										<?php       
											if($res == -1)
												{ 
													echo "<input type=text pattern='^[ 0-9]+(\.\d{2})?' placeholder=' 0.00' name=deviceIn value='' required>";  
    											}
    										else
    											{
    												echo "<input type=text pattern='^[ 0-9]+(\.\d{2})?' placeholder=' 0.00' name=deviceIn value='".$row1['StartIndications']."' required>";  
    											}
										?>
								</td>
							</tr>

								<tr> 
								<td><label>Модель прибора: </label> </td>
								<td><select class='select_db' name="model">
										<?php
										
										$query = "SELECT * FROM `DeviceModels` ORDER BY DeviceModels.Name";
										$result_sel = mysqli_query($link, $query);

										while ($row = mysqli_fetch_array($result_sel)) 
										{
											if($row['ModelId']==$row1['ModelId'])
												{ 
													echo "<option selected value = ".$row['ModelId'].">".$row['Name']." </option> "; 
    											}
    										else
    											{
    												echo "<option  value = ".$row['ModelId'].">".$row['Name']." </option> ";  
    											}
										}											
										?>
									</select>
								</td>
							</tr>
							<tr> 
								<td><label>Дата следующей проверки: </label> </td>
										<td>
											<?php
											if($res == -1)
												{ echo "<input type=date id=start name=dateNext 
											value=date('d.m.Y')
    										 required></td>";  
    											}
    										else
    											{
    										echo "<input type=date id=start name=dateNext 
    										value=".$row1['NextDateCheck']."
    										 required></td>";  
    											}
    										?>

							</tr>
								<tr> 
								<td><label>Управляющая комания: </label> </td>
								<td><select class='select_db' name="Company">
										<?php
										
										$query = "SELECT * FROM `ManagementCompany` WHERE ManagementCompany.CompanyId = ".$company_admin['CompanyId']." ORDER BY ManagementCompany.Name";
										$result_sel = mysqli_query($link, $query);

										while ($row = mysqli_fetch_array($result_sel)) 
										{
											if($row['CompanyId']==$row1['CompanyId'])
												{ 
													echo "<option selected value = ".$row['CompanyId'].">".$row['Name']." </option> "; 
    											}
    										else
    											{
    												echo "<option  value = ".$row['CompanyId'].">".$row['Name']." </option> ";  
    											}
										}											
										?>
									</select>
								</td>
							</tr>


						</tbody>

					</table>
							</div>
							<div>
							    <table width='100%'>
								<tr>
									<td>
										<input type="submit" class="confirm" value="Сохранить">
										<?php 
            								echo "<a href='/ManagementCompany/?table=Devices'>Отменить</a>";
            							?>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
    											echo "<a href='../index.php?table=DeviceIndications&DeviceId=" . $res . "' target='_blank'>Показания приборов учета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=DeviceEvents&DeviceId=" . $res . "' target='_blank'>События приборов учёта</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        									echo "</td>";
        								echo "</tr>";
        								echo "<tr>";
    										echo "<td>";
        										echo "<a href='../index.php?table=CommonDevices&DeviceId=" . $res . "' target='_blank'>Общедомовые приборы учёта</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=AccountDevices&DeviceId=" . $res . "' target='_blank'>Индивидуальные приборы</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
    										echo "</td>";
										echo "</tr>";
									}
								?>
							</table>
							</div>
							<input type="hidden" name="elem_table" value="Devices">
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

