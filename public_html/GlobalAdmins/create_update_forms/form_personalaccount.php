<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
?>

<html>
	<body>
		<?php
		    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$res = $_GET["AccountId"];
			
			if($res != -1)
			{
			    $query1 = "SELECT 
					PersonalAccounts.AccountId AS AccountId, 
					PersonalAccounts.Name AS Name, 
					ManagementCompany.RegionId AS RegionId, 
					PersonalAccounts.CompanyId AS CompanyId, 
					PersonalAccounts.AbonentId AS AbonentId, 
					PersonalAccounts.ObjectId AS ObjectId, 
					PersonalAccounts.StartDate AS StartDate, 
					PersonalAccounts.EndDate AS EndDate, 
					PersonalAccounts.Using AS 'Using'
		    		FROM PersonalAccounts AS PersonalAccounts, ManagementCompany AS ManagementCompany 
					WHERE PersonalAccounts.AccountId = '".$res."' AND ManagementCompany.CompanyId = PersonalAccounts.CompanyId";
		    	$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
				
                if ( !$row0 = mysqli_fetch_array( $result1 ) ) 
				{
				$row0 = array('AccountId'             => -1, 
							  'Name'                  => '',
							  'CompanyId'             => '',
							  'AbonentId'             => '',
							  'ObjectId'              => '',
							  'StartDate'             => date( 'd.m.Y H:i:s' ),
							  'EndDate'               => date( 'd.m.Y H:i:s' ),
							  'Using'                 => '' );
				}
			}
			//print_r($arrayZn);
		?>
		
		<div class = "main">
			<div class = "container content">
				<div class = "main__left">
					<div class = "container">
						<div class = "content__title">
							<?php
								//if($res == -1)
								//{
									//echo "<h5>Добавление нового лицевого счёта</h5>";	
								//}
								//else 
								//{
									//echo "<h5>Редактирование лицевого счёта</h5>";	
								//}
							?>
							<h5>Лицевой счет</h5>
						</div>
						
						<form class = "" method = "post" action = "/GlobalAdmins/create_update_query/update_query_personalaccount.php">
						
							<input type = "hidden" name = "elem_id" value = '<?php echo $res?>'>
							
							<div class = "form_wrapper">
								<table>
									<tbody>
										<tr>
											<td>
												<label>Номер лицевого счёта</label>
											</td>
											
											<td>
												<?php   
													if($res == -1)
													{
													 	echo "<input type = text name = name value = '' required>";	
													}
													else 
													{
														echo "<input type = text name = name value = '".$row0['Name']."' required>";	
													}
												?>
											</td>
										</tr>
										
										<tr>
											<td>
												<label>Абонент</label>
											</td>
											
											<td>
												<select class = 'select_db' name = "user" required>
												<?php
												    
													$query1 = "SELECT AbonentId, Name FROM Abonents";
													$result1 =  mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
													
													echo "<option disabled>Выберите абонента</option>";
													
													if($res == -1)
													{
														while ($row1 = mysqli_fetch_array($result1)) 
														{
															echo "<option value = ".$row1['AbonentId'].">".$row1['Name']." </option> ";
														}
													}
													else 
													{
														while ($row1 = mysqli_fetch_array($result1)) 
														{
															if($row0['AbonentId'] == $row1['AbonentId'])
															{
																echo "<option selected value = ".$row1['AbonentId'].">".$row1['Name']." </option> ";
															}
															else
															{
																echo "<option value = ".$row1['AbonentId'].">".$row1['Name']." </option> ";
															}
														}
													}
												?>
												</select>
											</td>
										</tr>
										
										<tr>
											<td>
												<label>Регион</label>
											</td>
											
											<td>
												<select name = "regionId" class = "select_region select_db" required>
												    
													<option disabled>Выберите регион</option>
													<?php
													    echo "<option value = 'NULL'>Не указано</option>";
														$query = "SELECT * FROM `Regions`";
														$result_sel = mysqli_query($link, $query);
														
														if($res == -1)
														{
															while ($row = mysqli_fetch_array($result_sel)) 
															{
																echo "<option class = 'option_region' value = ".$row['RegionId'].">" . $row['RegionСode'] . ", " . $row['Name'] . "</option>";
															}
														}
														else 
														{
															while ($row = mysqli_fetch_array($result_sel)) 
															{
																if($row0['RegionId'] == $row['RegionId'])
																{
																	echo "<option class = 'option_region' selected value = ".$row['RegionId'].">".$row['RegionСode'].", ".$row['Name']." </option> ";
																}
																else
																{
																	echo "<option class = 'option_region' value = ".$row['RegionId'].">".$row['RegionСode'].", ".$row['Name']." </option>";
																}
															}
														}
													?>
												</select>
											</td>
										</tr>
										
										<!-- Список управляющих компаний выводится динамически в зависимости от RegionId -->
										
										<tr>
											<td>
												<label>Управляющая компания</label>
											</td>
											
											<td>
												<select name = "companyId" class = "select_company select_db" required>
												
												    <option disabled>Выберите компанию</option>
													<?php
													    echo "<option value = 'NULL'>Не указано</option>";
														$query = "SELECT * FROM `ManagementCompany`";
														$result_sel = mysqli_query($link, $query);
														
														if($res == -1)
														{
															while ($row = mysqli_fetch_array($result_sel)) 
															{
																echo "<option class = 'option_region' value = ".$row['CompanyId'].">".$row['Name']." </option>";
															}
														}
														else 
														{
															while ($row = mysqli_fetch_array($result_sel)) 
															{
																if($row0['CompanyId'] == $row['CompanyId'])
																{
																	echo "<option class = 'option_region' selected value = ".$row['CompanyId'].">".$row['Name']." </option> ";
																}
																else
																{
																	echo "<option class = 'option_region' value = ".$row['CompanyId'].">".$row['Name']." </option>";
																}
															}
														}
													?>
													
												</select>
											</td>
										</tr>
										
										<!-- Список адресов выводится динамически в зависимости от CompanyId -->
										
										<tr>
											<td>
												<label>Объект недвижимости</label>
											</td>
											
											<td>
												<select name = "objectId" class = "select_address select_db" required>
													
													<option disabled>Выберите объект</option>
													<?php
														echo "<option value = 'NULL'>&nbsp</option>";
														$query = "SELECT 
																	   Objects.ObjectId AS ObjectId,
																	   Objects.Name AS Name,
																	   Objects.KadastrNo AS KadastrNo
																   FROM Objects AS Objects
																   ORDER BY Objects.Name";
														$result_sel = mysqli_query($link, $query);
														
														while ( $row = mysqli_fetch_array( $result_sel ) ) 
														{
															echo "<option " . ( ( $row0[ 'ObjectId' ] == $row[ 'ObjectId' ] ) ? "selected " : "" ) . " value='" . $row[ 'ObjectId' ] . "'>" . $row[ 'Name' ] . ( empty( $row[ 'KadastrNo' ] ) ? "" : " (" . $row[ 'KadastrNo' ] . ")" ) . "</option>";
														}
													?>
												</select>
											</td>
										</tr>
										
										<!-- Список всей инфы по объекту тоже выводится динамически в зависимости от ObjectId -->
										
										<!--
										<tr>
											<td>
												<label>Кадастровый номер</label>
											</td>
											
											<td>
											    <input class = "input_cadastr" type = "text" disabled placeholder = "Кадастровый номер">
											</td>
										</tr>
										-->
										
										<tr>
											<td>
												<label>Тип объекта</label>
											</td>
											
											<td>
											    <input class = "input_type" type = "text" disabled placeholder = "Тип объекта">
											</td>
										</tr>
										
										<tr>
											<td>
												<label>Площадь</label>
											</td>
											
											<td>
												<input class = "input_square" type = "text" disabled placeholder = "Плошадь">
											</td>
										</tr>

										<tr>
											<td>
												<label>Дата начала</label>
											</td>
											
											<td>
												<?php
											if($res == -1)
											{ 
												echo "<input type = date id = start name = start_date value = date( 'Y-m-d' )></td>";  
											}
    										else
											{
												echo "<input type = date id = start name = start_date value = '".$row0['StartDate']."'></td>";  
											}
    										?>
											</td>
										</tr>

										<tr>
											<td>
												<label>Дата окончания</label>
											</td>
											
											<td>
												<?php
											if($res == -1)
											{
												echo "<input type = date id = start name = end_date value = date( 'Y-m-d' ) min = '2016-01-01' max = '2050-12-31' required></td>";
											}
    										else
											{
												echo "<input type = date id = start name = end_date value = '".$row0['EndDate']."' min = '2016-01-01' max = '2050-12-31' required></td>";
											}
    										?>
											</td>
										</tr>

										<tr>
											<td>
												<label>Используется</label>
											</td>
											
											<td>
												<?php
													if($res == -1)
													{ 
														echo "<input type = checkbox name = 'used'>";
													}
													else
													{
														echo "<input type = checkbox " .( ( $row0[ 'Using' ] == 1 ) ? "checked " : "" ) . " name = 'used'>";
													}
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							
							<div>
								<table width = '100%'>
									<tr>
										<td>
											<input type = "submit" class = "confirm" value = "Сохранить">
											<?php 
												echo "<a href = '/GlobalAdmins/index.php?table=PersonalAccounts'>Отменить</a>";
											?>
										</td>
									</tr>
									<?php
										if ( $res != -1 ) {
											echo "<tr><td><br><b>См. также:</b></td></tr>";
											echo "<tr>";
												echo "<td>";
													echo "<a href = '../index.php?table=AccountDevices&AccountId=" . $res . "' target='_blank'>Приборы учетов по лицевым счетам</a>";
													echo "&nbsp;&nbsp;&nbsp;";
													
													echo "<a href = '../index.php?table=AccountNormatives&AccountId=" . $res . "' target='_blank'>Виды нормативов по лицевым счетам</a>";
													echo "&nbsp;&nbsp;&nbsp;";
													
													echo "<a href = '../index.php?table=AccountServices&AccountId=" . $res . "' target='_blank'>Состав коммунальных услуг</a>";
													echo "&nbsp;&nbsp;&nbsp;";
												echo "</td>";
											echo "</tr>";
										}
									?>
							    </table>
							</div>
							
							<input type = "hidden" name = "elem_table" value = "<?php echo $table ?>">
							
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
	
	<script>
		$(document).ready(function(){
		    
		    //Изменение содержимого компаний
			$('select.select_region').on('change', function(){
			    var res;
				var val = $(this).val();
				
				$('select.select_address').empty();
                $('select.select_address').append('<option disabled selected>Выберите адрес</option>');
                
                $('input.input_cadastr').val("");
                $('input.input_type').val("");
                $('input.input_square').val("");
				
                $.ajax({
                    method: "POST",
                    url: "company_query.php",
                    data: { id: val },
                    dataType: 'JSON'
                })
                .done(function( response ) {
                    $('select.select_company').empty();
                    $('select.select_company').append('<option disabled selected>Выберите компанию</option>');
                    
                    var len = response[0].length;
                    var keys = Object.values(response[0]);
                    var values = Object.values(response[1]);

                    for(var i=0; i<len; i++){
                        $('select.select_company').append('<option value="' + keys[i] + '">' + values[i] + '</option>');
                    }
                });
            });
            
            //Изменение содержимого объектов недвижимости
			$('select.select_company').on('change', function(){
			    var res;
				var val = $(this).val();
				
                $('input.input_cadastr').val("");
                $('input.input_type').val("");
                $('input.input_square').val("");
				
                $.ajax({
                    method: "POST",
                    url: "object_query.php",
                    data: { id: val },
                    dataType: 'JSON'
                })
                .done(function( response ) {
                    $('select.select_address').empty();
                    $('select.select_address').append('<option disabled selected>Выберите объект</option>');
                    
                    var len = response[0].length;
                    var keys = Object.values(response[0]);
                    var values = Object.values(response[1]);

                    for(var i=0; i<len; i++){
                        $('select.select_address').append('<option value="' + keys[i] + '">' + values[i] + '</option>');
                    }
                });
            });
            
            //Изменение содержимого данных по адресу
			$('select.select_address').on('change', function(){
			    var res;
				var val = $(this).val();
				
                $.ajax({
                    method: "POST",
                    url: "object_data_query.php",
                    data: { id: val },
                    dataType: 'JSON'
                })
                .done(function( response ) {

                    
                    console.log(response);

                    var name = response[0];
                    var type = response[1];
                    var square = response[2];
                    
                    $('input.input_cadastr').val(name);
                    
                    switch(type) {
                        case '1':
                            $('input.input_type').val("Территория");
                        break;
                        case '2':
                            $('input.input_type').val("Участок");
                        break;
                        case '3':
                            $('input.input_type').val("Здание");
                        break;
                        case '4':
                            $('input.input_type').val("помещение и т.д.");
                        break;
                    }
                    
                    $('input.input_square').val(square);
                });
            });
		});
	</script>
	
	<script>
	    //Вывод данных по объекту при загрузке страницы
		$(document).ready(function(){
			var res;
			var val = $('select.select_address').val();
			
			$.ajax({
				method: "POST",
				url: "object_data_query.php",
				data: { id: val },
				dataType: 'JSON'
			})
			.done(function( response ) {
				
				console.log(response);

				var name = response[0];
				var type = response[1];
				var square = response[2];
				
				$('input.input_cadastr').val(name);
				
				switch(type) {
					case '1':
						$('input.input_type').val("Территория");
					break;
					case '2':
						$('input.input_type').val("Участок");
					break;
					case '3':
						$('input.input_type').val("Здание");
					break;
					case '4':
						$('input.input_type').val("помещение и т.д.");
					break;
				}
				
				$('input.input_square').val(square);
			});
		});
	</script>
</html>