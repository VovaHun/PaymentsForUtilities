<?php
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
?>
<body>
	<?php
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$accountId= $_GET["AccountId"];
		$serviceId= $_GET["ServiceId"];
		$tariffId= $_GET["TariffId"];
		$date = $_GET['Date'];
		$fn  = $names_ar[ 'AccountServices' ];
	
		$nowdate = date( 'Y-m-d H:i:s' );
		
		
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Состав коммунальных услуг</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_accountservices.php">
							<input type="hidden" name="elem_id" value='<?php echo $accountId?>'>
							<input type="hidden" name="elem_service" value='<?php echo $serviceId?>'>
							<input type="hidden" name="elem_tariff" value='<?php echo $tariffId?>'>
							<input type="hidden" name="elem_date" value='<?php echo $date?>'>
							<div class="form_wrapper">
								<table>
									<?php
										$query1 ="SELECT PersonalAccounts.AccountId, PersonalAccounts.Name FROM PersonalAccounts 
											WHERE PersonalAccounts.CompanyId = ".$company_admin['CompanyId']."
											ORDER BY PersonalAccounts.Name";
										$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

										$query2 ="SELECT Services.ServiceId, Services.Name, ManagementCompany.Name
									          FROM Services 
									          LEFT JOIN ManagementCompany AS ManagementCompany ON (Services.CompanyId = ManagementCompany.CompanyId)
									          WHERE Services.CompanyId = ".$company_admin['CompanyId']."
									          ORDER BY Services.Name ";
										$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link)); 

										$query3 ="SELECT TariffTypes.TariffId, TariffTypes.Name, TariffTypes.IsNormative FROM TariffTypes ORDER BY TariffTypes.Name";
										$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link));

										if ($accountId != -1){
											$query0 ="SELECT AccountServices.AccountId, AccountServices.ServiceId, AccountServices.TariffId, AccountServices.CalculationType, AccountServices.Date, AccountServices.Using, AccountServices.Portion, AccountServices.Сoefficient
                      							FROM AccountServices AS AccountServices 
                      							WHERE
                      								AccountServices.AccountId = ".$accountId." 
                      							    AND AccountServices.ServiceId=".$serviceId." 
                      							    AND AccountServices.TariffId=".$tariffId."
                      							    AND AccountServices.Date='".$date."'";
											$result0 = mysqli_query($link, $query0) or die("Ошибка " . mysqli_error($link)); 

											$num_columns = $result0->field_count;

											while($row0 = mysqli_fetch_array($result0)){
												echo "<tr>";
													echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[0]'>";
												        
													while($row1 = mysqli_fetch_array($result1)){
														if($row1[0] == $row0['AccountId']){
															echo "<option selected value=".$row1[0].">".$row1[1]."</option>";
														}
														else echo "<option value=".$row1[0].">".$row1[1]."</option>";
													}
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_1'>".$fn[1].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[1]'>";
												        
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
												    echo "<select class='select_db' name='fn[2]'>";
												   
														while($row3 = mysqli_fetch_array($result3)){
															if($row3[0] == $row0['TariffId']){
																echo "<option selected value=".$row3[0].">".$row3[1]."</option>";
																$TariffId = $row3[0];
																$IsNormative =  $row3[2];
																
															}
															else echo "<option value=".$row3[0].">".$row3[1]."</option>";
														}
														if (!isset($row0['TariffId'])) {
															echo "<option selected value='NULL'>Отсутствует</option>";
														}
														else echo "<option value='NULL'>Отсутствует</option>";
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_3'>".$fn[3].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[3]'>";
												        
												    
												    	echo "<option " . ( ( $row0[ 'CalculationType' ] == 1 ) ? "selected " : "" ) . " value='1'>По нормативам потребления</option>";
												    	echo "<option " . ( ( $row0[ 'CalculationType' ] == 2 ) ? "selected " : "" ) . " value='2'>По показаниям приборов учета</option>";
												    	echo "<option " . ( ( $row0[ 'CalculationType' ] == 3 ) ? "selected " : "" ) . " value='3'>По показаниям приборов учета</option>";
												    	echo "<option " . ( ( $row0[ 'CalculationType' ] == 4 ) ? "selected " : "" ) . " value='4'>Пропорционально площадей объектов недвижимости</option>";
												    	echo "<option " . ( ( $row0[ 'CalculationType' ] == 5 ) ? "selected " : "" ) . " value='5'>Пропорционально показаниям индивидуальных и общедомовых приборов учета</option>";
													   
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_4'>".$fn[4].":</label></td>";
													
													echo "<td>";
													echo "<input type=datetime-local name='fn[4]'
													value='" . date( 'Y-m-d\TH:i:s', strtotime( 
													    $row0[ 'Date' ] ) ) . "' required>";   
													echo "</td>";

												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_5'>".$fn[5].":</label></td>";
													echo "<td>";
												    echo "<input type=checkbox " . ( ( $row0[ 'Using' ] == 1 ) ? "checked " : "" ) . "  name='fn[5]'>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_6'>".$fn[6].":</label></td>";
													echo "<td><input type='text' id='elem_6' name='fn[6]' value='".$row0["6"]."'></td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_7'>".$fn[7].":</label></td>";
													echo "<td><input type='text' id='elem_7' name='fn[7]' value='".$row0["7"]."'></td>";
												echo "</tr>";
											}
										}
										else{
											echo "<tr>";
												echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[0]'>";
											        
												while($row1 = mysqli_fetch_array($result1)){
													echo "<option value=".$row1[0].">".$row1[1]."</option>";
												}
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_1'>".$fn[1].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[1]'>";
											        
												while($row2 = mysqli_fetch_array($result2)){
													echo "<option value=".$row2[0].">".$row2[1] . ( empty($row2[2]) ? "" : " (".$row2[2].")" ) ."</option>";
												}
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_2'>".$fn[2].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[2]'>";
											    // echo "<option selected value='NULL'>&nbsp</option>";    
												while($row3 = mysqli_fetch_array($result3)){
													echo "<option value=".$row3[0].">".$row3[1]."</option>";
												}
												//echo "<option value='NULL'>Отсутствует</option>";
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_3'>".$fn[3].":</label></td>";
												echo "<td>";
												    echo "<select class='select_db' name='fn[3]'>";
												        
												    	echo "<option value='1'>По нормативам потребления</option>";
												    	echo "<option value='2'>По показаниям приборов учета</option>";
												    	echo "<option value='3'>Фиксированной суммой</option>";
												    	echo "<option value='4'>Пропорционально площадей объектов недвижимости</option>";
												    	echo "<option value='5'>Пропорционально показаниям индивидуальных и общедомовых приборов учета</option>";
													echo "</select>";
													echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_3'>".$fn[4].":</label></td>";
												
												echo "<td>";
												echo "<input type=datetime-local name='fn[4]'
												
													value='".date( 'Y-m-d\TH:i:s', strtotime( $nowdate ) )."' required>"; 
												echo "</td>";

											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_5'>".$fn[5].":</label></td>";
												echo "<td>";
											   echo "<input type=checkbox name='fn[5]'>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_6'>".$fn[6].":</label></td>";
												echo "<td><input type='text' id='elem_6' name='fn[6]' value=''></td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_7'>".$fn[7].":</label></td>";
												echo "<td><input type='text' id='elem_7' name='fn[7]' value=''></td>";
											echo "</tr>";
										}
									?>
								</table>
							</div>
							<input type="hidden" name="elem_table" value="AccountServices">
							<input type="submit" class="confirm" value="Сохранить">
							<a href="/ManagementCompany/?table=AccountServices">Отменить</a>
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