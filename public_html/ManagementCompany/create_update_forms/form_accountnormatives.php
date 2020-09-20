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
		$tariffIdId= $_GET["TariffId"];
		$date= $_GET["Date"];
		$fn  = $names_ar[ 'AccountNormatives' ];
		
		$nowdate = date( 'Y-m-d H:i:s' );
	//	print_r($fn);
		
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Вид норматива</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_accountnormatives.php">
							<input type="hidden" name="elem_id" value='<?php echo $accountId?>'>
							<input type="hidden" name="elem_service" value='<?php echo $serviceId?>'>
							<input type="hidden" name="elem_tariff" value='<?php echo $tariffIdId?>'>
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

										$query3 ="SELECT TariffTypes.TariffId, TariffTypes.Name FROM TariffTypes ORDER BY TariffTypes.Name";
										$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link));

										if ($accountId != -1){
											$query0 ="SELECT AccountNormatives.AccountId, AccountNormatives.ServiceId, AccountNormatives.TariffId, AccountNormatives.Date, AccountNormatives.Count, AccountNormatives.Using
                      							FROM AccountNormatives WHERE AccountNormatives.AccountId = ".$accountId." AND AccountNormatives.ServiceId=".$serviceId." 
                      							    AND AccountNormatives.TariffId=".$tariffIdId;

											$result0 = mysqli_query($link, $query0) or die("Ошибка " . mysqli_error($link)); 

											$num_columns = $result1->field_count;

											while($row0 = mysqli_fetch_array($result0)){
												echo "<tr>";
													echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[]'>";
												       
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
													echo "<input type=datetime-local name='fn[]'
													value='" . date( 'Y-m-d\TH:i:s', strtotime( 
													    $row0[ 'Date' ] ) ) . "' required>";  
													echo "</td>";

												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_4'>".$fn[4].":</label></td>";
													echo "<td><input type='text' id='elem_4' name='fn[]' value='".$row0["Count"]."'></td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for='elem_5'>".$fn[5].":</label></td>";
													echo "<td>";
												    echo "<input type=checkbox " . ( ( $row0[ 'Using' ] == 1 ) ? "checked " : "" ) . "name='fn[]'>";
													echo "";
												echo "</tr>";
											}
										}
										else{
											echo "<tr>";
												echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[]'>";
											    
												while($row1 = mysqli_fetch_array($result1)){
													echo "<option value=".$row1[0].">".$row1[1]."</option>";
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
												echo "<input type=datetime-local name='fn[]'
													value='".date( 'Y-m-d\TH:i:s', strtotime( $nowdate ) )."' required>";
												echo "</td>";
											
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_4'>".$fn[4].":</label></td>";
													echo "<td><input type='text' id='elem_4' name='fn[]' value='' ></td>";
												
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_5'>".$fn[5].":</label></td>";
												echo "<td>";
											   echo "<input type=checkbox name='fn[]'>";
												echo "</td>";
											echo "</tr>";
										}
									?>
								</table>
							</div>
							<input type="hidden" name="elem_table" value="AccountNormatives">
							<input type="submit" class="confirm" value="Сохранить">
							<a href="/ManagementCompany/?table=AccountNormatives">Отменить</a>
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