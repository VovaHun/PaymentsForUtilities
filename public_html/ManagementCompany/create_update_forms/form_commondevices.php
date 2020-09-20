<?php
   require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';

?>
<body>
	<?php
		$objectId = $_GET["ObjectId"];
		$serviceId = $_GET["ServiceId"];
		$tariffId = $_GET["TariffId"];
		$deviceId = $_GET["DeviceId"];
		$date = $_GET["Date"];
		$fn  = $names_ar[ 'CommonDevices' ];
		
	//	print_r($res);
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Общедомовые приборы учета</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_commondevices.php">
							<input type="hidden" name="elem_id" value='<?php echo $objectId?>'>
							<input type="hidden" name="elem_service" value='<?php echo $serviceId?>'>
							<input type="hidden" name="elem_tariff" value='<?php echo $tariffId?>'>
							<input type="hidden" name="elem_device" value='<?php echo $deviceId?>'>
							<input type="hidden" name="elem_date" value='<?php echo $date?>'>
							<div class="form_wrapper">
								<table>
									<?php
										$query1 = "SELECT 
    														   Objects.ObjectId AS ObjectId,
    														   Objects.Name AS Name,
    														   Objects.KadastrNo AS KadastrNo
    													   FROM Objects AS Objects
    													   WHERE Objects.CompanyId=".$company_admin['CompanyId']."
    													   ORDER BY Objects.Name";
            											$result1 = mysqli_query( $link, $query1 ) or die( "Ошибка: " . mysqli_error( $link ) );

										$query2 ="SELECT Services.ServiceId, Services.Name, ManagementCompany.Name
									          FROM Services 
									          LEFT JOIN ManagementCompany AS ManagementCompany ON (Services.CompanyId = ManagementCompany.CompanyId)
									          WHERE Services.CompanyId = ".$company_admin['CompanyId']."
									          ORDER BY Services.Name ";
										$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link)); 

										$query3 ="SELECT TariffTypes.TariffId, TariffTypes.Name FROM TariffTypes ORDER BY TariffTypes.Name";
										$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link));

										$query4 ="SELECT Devices.DeviceId, Devices.Name FROM Devices 
										WHERE Devices.CompanyId = ".$company_admin['CompanyId']."
										ORDER BY Devices.Name";
										$result4 = mysqli_query($link, $query4) or die("Ошибка " . mysqli_error($link));

										if ($objectId != -1){
											$query0 ="SELECT CommonDevices.ObjectId, CommonDevices.ServiceId, CommonDevices.TariffId, CommonDevices.DeviceId, CommonDevices.Date, CommonDevices.Using FROM CommonDevices 
												WHERE CommonDevices.ObjectId = " .$objectId."
												AND CommonDevices.ServiceId =".$serviceId."
												AND CommonDevices.TariffId = ".$tariffId."
												AND CommonDevices.DeviceId =".$deviceId."
												AND CommonDevices.Date='".$date."'";

											$result0 = mysqli_query($link, $query0) or die("Ошибка " . mysqli_error($link)); 

											
											while($row0 = mysqli_fetch_array($result0)){
												echo "<tr>";
													echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[]'>";
    												     
            											
            											while ( $row1 = mysqli_fetch_array( $result1 ) ) {
            												echo "<option " . ( ( $row0[ 'ObjectId' ] == $row1[ 'ObjectId' ] ) ? "selected " : "" ) . " value='" . $row1[ 'ObjectId' ] . "'>" . $row1[ 'Name' ] . ( empty( $row1[ 'KadastrNo' ] ) ? "" : " (" . $row1[ 'KadastrNo' ] . ")" ) . "</option>";
            											}
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_1'>".$fn[1].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[]'>";
												        
													while($row2 = mysqli_fetch_array($result2)){
														if($row2[0] == $row0['ServiceId']){
															echo "<option selected value=".$row2[0].">".$row2[1] . ( empty($row2[2]) ? "" : " (".$row2[2].")" ) ."</option>";
														}
														else echo "<option value=".$row2[0].">".$row2[1] . ( empty($row2[2]) ? "" : " (".$row2[2].")" ) ."</option>";
													}
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_2'>".$fn[2].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[]'>";
												        
													while($row3 = mysqli_fetch_array($result3)){
														if($row3[0] == $row0['TariffId']){
															echo "<option selected value=".$row3[0].">".$row3[1]."</option>";
														}
														else echo "<option value=".$row3[0].">".$row3[1]."</option>";
													}
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_3'>".$fn[3].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[]'>";
												        
													while($row4 = mysqli_fetch_array($result4)){
														if($row4[0] == $row0['DeviceId']){
															echo "<option selected value=".$row4[0].">".$row4[1]."</option>";
														}
														else echo "<option value=".$row4[0].">".$row4[1]."</option>";
													}
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_4'>".$fn[4].":</label></td>";
													
													echo "<td>";
													echo "<input type=date name='fn[]' value=".$row0["Date"]." min='2016-01-01' max='2050-12-31' >";  
													echo "</td>";

												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_5'>".$fn[5].":</label></td>";
													echo "<td>";
												    echo "<input type=checkbox " . ( ( $row0["Using"] == 1 ) ? "checked " : "" ) . "name=fn[]' >";
												   	echo "</td>";
												echo "</tr>";
											}
										}
										else{
											echo "<tr>";
												echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[]'>";
    											    
												while ( $row1 = mysqli_fetch_array( $result1 ) ) {
            												echo "<option " . ( ( $row0[ 'ObjectId' ] == $row1[ 'ObjectId' ] ) ? "selected " : "" ) . " value='" . $row1[ 'ObjectId' ] . "'>" . $row1[ 'Name' ] . ( empty( $row1[ 'KadastrNo' ] ) ? "" : " (" . $row1[ 'KadastrNo' ] . ")" ) . "</option>";
            											}
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_1'>".$fn[1].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[]'>";
											        
												while($row2 = mysqli_fetch_array($result2)){
													echo "<option value=".$row2[0].">".$row2[1] . ( empty($row2[2]) ? "" : " (".$row2[2].")" ) ."</option>";
												}
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_2'>".$fn[2].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[]'>";
											        
												while($row3 = mysqli_fetch_array($result3)){
													echo "<option value=".$row3[0].">".$row3[1]."</option>";
												}
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_3'>".$fn[3].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[]'>";
											        
												while($row4 = mysqli_fetch_array($result4)){
													echo "<option value=".$row4[0].">".$row4[1]."</option>";
												}
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_4'>".$fn[4].":</label></td>";
																				    
												echo "<td>";
												echo "<input type=date name='fn[]' value=".date('d.m.Y')."  >";  
												echo "</td>";

											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_5'>".$fn[5].":</label></td>";
												echo "<td>";
												echo "<input type=checkbox name='fn[]' >";
												echo "</td>";
											echo "</tr>";
										}
									?>
								</table>
							</div>
							<input type="hidden" name="elem_table" value="CommonDevices">
							<input type="submit" class="confirm" value="Сохранить">
							<a href="/ManagementCompany/?table=CommonDevices">Отменить</a>
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