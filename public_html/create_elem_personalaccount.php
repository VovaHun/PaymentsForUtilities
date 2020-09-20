<?php
	require $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
	require $_SERVER['DOCUMENT_ROOT'].'/includes/user_header.php';
?>

<html>
	<body>
		<div class = "main">
			<div class = "container content">
				<div class = "main__left">
					<div class = "container">
						<div class = "content__title">
							<h5>Добавление нового лицевого счёта</h5>
						</div>
						
						<form class = "" method = "post" action = "/update_elem_personalaccount.php">
							
							<div class = "form_wrapper">
							
								<table>
									<tbody>
										<tr>
											<td>
												<label>Номер лицевого счёта, комментарий</label>
											</td>
											
											<td>
												<input type = text name = "name" value = "<?php echo @$_POST['name']; ?>" required>
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
														
														while ($row = mysqli_fetch_array($result_sel)) 
														{
															echo "<option class = 'option_region' value = ".$row['RegionId'].">" . $row['RegionСode'] . ", " . $row['Name'] . "</option>";
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
												<select disabled name = "companyId" class = "select_company select_db" required>
												
												    <option disabled>Выберите компанию</option>
													<?php
													    //echo "<option value = 'NULL'>Не указано</option>";
														$query = "SELECT * FROM `ManagementCompany`";
														$result_sel = mysqli_query($link, $query);
														
														while ($row = mysqli_fetch_array($result_sel)) 
														{
															echo "<option class = 'option_region' value = ".$row['CompanyId'].">".$row['Name']." </option>";
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
												<select disabled name = "objectId" class = "select_address select_db" required>
													
													<option disabled>Выберите объект</option>
													<?php
														$query = "SELECT 
																	   Objects.ObjectId AS ObjectId,
																	   Objects.Name AS Name,
																	   Objects.KadastrNo AS KadastrNo
																   FROM Objects AS Objects
																   ORDER BY Objects.Name";
														$result_sel = mysqli_query($link, $query);
														
														while ( $row = mysqli_fetch_array( $result_sel ) ) 
														{
															echo "<option value='" . $row[ 'ObjectId' ] . "'>" . $row[ 'Name' ] . ( empty( $row[ 'KadastrNo' ] ) ? "" : " (" . $row[ 'KadastrNo' ] . ")" ) . "</option>";
														}
													?>
												</select>
											</td>
										</tr>
										
										<!-- Список всей инфы по объекту тоже выводится динамически в зависимости от ObjectId -->
										
										<tr>
											<td>
												<label>Тип объекта</label>
											</td>
											
											<td>
											    <input class="input_type" type="text" disabled placeholder="Тип объекта">
											</td>
										</tr>
										
										<tr>
											<td>
												<label>Площадь</label>
											</td>
											
											<td>
												<input class="input_square" type="text" disabled placeholder="Плошадь">
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							
							<input type = "submit" class = "confirm" value = "Сохранить">
							
							<?php
								echo "<a href = '/'>Отменить</a>";
							?>
							
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
                $('select.select_address').prop('disabled', true);
                
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
                    $('select.select_company').prop('disabled', false);
                });
            });
            
            
            //Изменение содержимого адресов
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
                    $('select.select_address').append('<option disabled selected>Выберите адрес</option>');
                    
                    var len = response[0].length;
                    var keys = Object.values(response[0]);
                    var values = Object.values(response[1]);

                    for(var i=0; i<len; i++){
                        $('select.select_address').append('<option value="' + keys[i] + '">' + values[i] + '</option>');
                    }
                    $('select.select_address').prop('disabled', false);
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
</html>
	