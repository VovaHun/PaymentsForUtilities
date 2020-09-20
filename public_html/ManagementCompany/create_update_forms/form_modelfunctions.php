<?php
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
?>
<body>
	<?php
		$modelId = $_GET["ModelId"];
		$tariffId = $_GET["TariffId"];
		$fn  = $names_ar[ 'ModelFunctions' ];
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Функции моделей устройств</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_modelfunctions.php">
							<input type="hidden" name="elem_id" value='<?php echo $modelId?>'>
							<input type="hidden" name="elem_tariff" value='<?php echo $tariffId ?>'>
							<div class="form_wrapper">
								<table>
									<?php
										$query1 ="SELECT DeviceModels.ModelId, DeviceModels.Name FROM DeviceModels ORDER BY DeviceModels.Name";
										$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

										
									
										$query3 ="SELECT TariffTypes.TariffId, TariffTypes.Name 
												  FROM TariffTypes 
												  LEFT JOIN Tariffs AS Tariffs ON (Tariffs.TariffId = TariffTypes.TariffId) 
												  WHERE Tariffs.CompanyId = ".$company_admin['CompanyId']." 
												  ORDER BY TariffTypes.Name";
										$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link)); 

										if ($modelId != -1){
											$query0 ="SELECT ModelFunctions.ModelId,  ModelFunctions.TariffId, ModelFunctions.Using FROM ModelFunctions WHERE ModelFunctions.ModelId = ".$modelId. "
											    AND ModelFunctions.TariffId =".$tariffId;
											$result0 = mysqli_query($link, $query0) or die("Ошибка " . mysqli_error($link)); 

											$num_columns = $result1->field_count;

											while($row0 = mysqli_fetch_array($result0)){
												echo "<tr>";
													echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[]'>";
												        
													while($row1 = mysqli_fetch_array($result1)){
														if($row1[0] == $row0['ModelId']){
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
													echo "<td><label for='elem_2'>".$fn[2].":</label></td>";
													echo "<td>";
												    
												    echo"<input type=checkbox " . ( ( $row0[ 'Using' ] == 1 ) ? "checked " : "" ) . "name='fn[]'>";
												    
										
													echo "</td>";
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
											       
												while($row3 = mysqli_fetch_array($result3)){
													echo "<option value=".$row3[0].">".$row3[1]."</option>";
												}
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for='elem_2'>".$fn[2].":</label></td>";
												echo "<td>";
											   // echo "<select name='fn[]'>";
										    	//	echo "<option value='0'>Нет</option>";
												//	echo "<option value='1'>Да</option>";
													
													
													echo "<input type=checkbox name='fn[]' >";
											//	echo "</select>";
												echo "</td>";
											echo "</tr>";
										}
									?>
								</table>
							</div>
							<input type="hidden" name="elem_table" value="ModelFunctions">
							<input type="submit" class="confirm" value="Сохранить">
							<a href='/ManagementCompany/?table=ModelFunctions'>Отменить</a>
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