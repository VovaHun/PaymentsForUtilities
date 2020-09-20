<?php
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
    
?>

<body>
	<?php
	    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	    
		$res = $_GET["TariffId"];
		@$res2 = $_GET["ServiceId"];
		@$res3 = $_GET["RegionId"];
		$fn  = $names_ar[ 'Tariffs' ];
		
		if ( $res != -1 ) {
			$query1 ="SELECT 
					Tariffs.TariffId AS TariffId,
					Tariffs.ServiceId AS ServiceId,
					Tariffs.RegionId AS RegionId,
					Tariffs.CompanyId AS CompanyId,
					Tariffs.Date AS 'Date',
					Tariffs.Price AS Price
					FROM Tariffs AS Tariffs WHERE Tariffs.TariffId=".$res." AND Tariffs.ServiceId=".$res2." AND Tariffs.RegionId=".$res3;

	        	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
			
			if ( !$row = mysqli_fetch_array( $result1 ) ) {
				$row = array( 'TariffId'              => '', 
							  'ServiceId'             => '',
							  'RegionId'              => '',
							  'Date'                  =>  date( 'd.m.Y ' ),
							  'Price'                  => '');
							 
			}
		}		
			
		//print_r($query1);
		
															
	?>
	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Тарифы</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_tariffs.php">
 							<input type="hidden" name="elem_id" value='<?php echo $res?>'>
 							<div class="form_wrapper">
								<table>
									<?php
									$query1 = "SELECT * FROM TariffTypes ORDER BY TariffTypes.Name";
									$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

									$query2 ="SELECT Services.ServiceId, Services.Name, ManagementCompany.Name
									          FROM Services 
									          LEFT JOIN ManagementCompany AS ManagementCompany ON (Services.CompanyId = ManagementCompany.CompanyId)
									          WHERE Services.CompanyId = ".$company_admin['CompanyId']."
									          ORDER BY Services.Name ";
									$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link));

									
									
									$query4 = "SELECT * FROM ManagementCompany
									WHERE ManagementCompany.CompanyId = ".$company_admin['CompanyId']."
									 ORDER BY ManagementCompany.Name";
									$result4 = mysqli_query($link, $query4) or die("Ошибка " . mysqli_error($link));

									$reg =  mysqli_fetch_array($result4);

									$query3 = "SELECT * FROM Regions WHERE RegionId=".$reg['RegionId']."
									ORDER BY Regions.RegionСode";
									$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link));
									?>

									<tbody>
									<tr> 
										<td><label>Вид тарифа: </label> </td>
										<td><select class='select_db' name="tarifftype">
										<?php       
										
										while ($row1 = mysqli_fetch_array($result1)) {
											if($row1['TariffId']==$row['TariffId']){
												echo "<option selected value = ".$row1['TariffId'].">".$row1['Name']." </option> ";
											}
											else{
												echo "<option value = ".$row1['TariffId'].">".$row1['Name']." </option> ";
											}		  	
										 }  	

										?>
										</select>
								</td>
							</tr>
			
							<tr> 
								<td><label>Коммунальная услуга: </label> </td>
								<td><select class='select_db' name="service">
									<?php       
										
										while($row2 = mysqli_fetch_array($result2)){
											if($row2[0] == $row['ServiceId']){
												echo "<option selected value=".$row2[0].">".$row2[1] . ( empty($row2[2]) ? "" : " (".$row2[2].")" ) ."</option>";
											}
											else echo "<option value=".$row2[0].">".$row2[1] . ( empty($row2[2]) ? "" : " (".$row2[2].")" ) ."</option>";
										} 	

										?>
								</select>
							</td>
							</tr>
							<tr> 
								<td><label>Регион: </label> </td>
								<td><select class='select_db' name="region">
									<?php       
										
										while ($row3 = mysqli_fetch_array($result3)) {
											if($row3['RegionId']==$row['RegionId']){
												echo "<option selected value = ".$row3['RegionId'].">".$row3['RegionСode'].", ".$row3['Name']." </option> ";
											}
											else{
												echo "<option value = ".$row3['RegionId'].">".$row3['RegionСode'].", ".$row3['Name']." </option> ";
											}		  	
										 }  	

										?>
								</select>
							</td>
							<tr> 
								<td><label>Управляющая компания: </label> </td>
								<td><select class='select_db' name="company">
									<?php       
										$result4 = mysqli_query($link, $query4) or die("Ошибка " . mysqli_error($link));
										while ($row4 = mysqli_fetch_array($result4)) {
											if($row4['CompanyId']==$row['CompanyId']){
												echo "<option selected value = ".$row4['CompanyId'].">".$row4['Name']." </option> ";
											}
											else{
												echo "<option value = ".$row4['CompanyId'].">".$row4['Name']."</option> ";
											}		  	
										 }  	

										?>
								</select>
							</td>
							</tr>

								<tr> 
								<td><label>Дата начала действия: </label> </td>
								<td>
									<?php  
									if($res == -1){
										echo "<input required type = date name=date value='' require>";
									}
									else{
										echo "<input required type = date name=date value=".$row['Date']." require>";
									}

									?>
								</td>
							</tr>
							<tr> 
								<td><label>Цена: </label> </td>
								<td>
								<?php  
									if($res == -1){
										echo "<input type =text name=price value='' require>";
									}
									else{
										echo "<input type =text name=price value='".$row['Price']."' require>";
									}

								?>
								</td>
							</tr>
						</tbody>

					</table>
							</div>
							<input type="hidden" name="OldTarId" value="<?php echo $res ?>">
							<input type="hidden" name="OldSerId" value="<?php echo $res2 ?>">
							<input type="hidden" name="OldRegId" value="<?php echo $res3 ?>">
							<input type="hidden" name="elem_table" value="Tariffs">
							<input type="submit" class="confirm" value="Сохранить">
							<?php 
								echo "<a href='/ManagementCompany/?table=Tariffs'>Отменить</a>";
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

