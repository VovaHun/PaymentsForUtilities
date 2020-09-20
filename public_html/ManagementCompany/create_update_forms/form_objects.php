<?php
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
?>
<body>
	<?php
		$res = $_GET["ObjectId"];
		$fn  = $names_ar[ 'Objects' ];
		//print_r($_POST);
	?>

	<div class = "main">
		<div class = "container content">
			<div class = "main__left">
				
					<div class = "container">
						<div class = "content__title">
							<h5>Объект недвижимости</h5>
						</div>
						<form class = "" method = "post" action = "/ManagementCompany/create_update_query/update_query_objects.php">
							<input type = "hidden" name = "elem_id" value = '<?php echo $res?>'>
							<div class = "form_wrapper">
								<table>
									<?php
										$query1 = "SELECT ManagementCompany.CompanyId, ManagementCompany.Name FROM ManagementCompany WHERE ManagementCompany.CompanyId = '".$company_admin['CompanyId']."' ORDER BY ManagementCompany.Name";
										$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

										if ($res != -1){
											$query0  = "SELECT Objects.Name, Objects.KadastrNo, Objects.ParentId, Objects.ObjectType, Objects.Square, Objects.Address, Objects.Comment, Objects.CompanyId FROM Objects WHERE Objects.ObjectId = ".$res;
											$result0 = mysqli_query($link, $query0) or die("Ошибка " . mysqli_error($link)); 

											$num_columns = $result1->field_count;

											while($row0 = mysqli_fetch_array($result0)){
												echo "<tr>";
													echo "<td><label for = 'elem_0'>".$fn[0].":</label></td>";
													echo "<td>";
													echo "<input type=text name = 'fn[]' value = '".$row0[0]."' required>"; 
													echo "</td>";
												echo "</tr>";
        
                                                echo "<tr>";
													echo "<td><label for = 'elem_1'>".$fn[1].":</label></td>";
													echo "<td>";
														echo "<input type=text name = 'fn[]' value = '".$row0[1]."' required>";  
													echo "</td>";
												echo "</tr>";
												
												// Вывод кадастровых номеров по id родителя
												//Переменная для вывода всех записей, в которых тип объекта меньше данного на единицу
												$parent_type = intval($row0[3]) - 1;

												$query2  = "SELECT Objects.ObjectId, Objects.KadastrNo FROM Objects WHERE Objects.ObjectType = ".$parent_type." AND Objects.CompanyId = '".$company_admin['CompanyId']."' ORDER BY Objects.KadastrNo";
												$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link));
												
												echo "<tr>";
													echo "<td><label for = 'elem_1'>".$fn[2].":</label></td>";
													echo "<td>";
												    echo "<select class = 'select_child select_db' name = 'fn[]'>";
												    echo "<option disabled>Выберите объект</option>";
														while($row2 = mysqli_fetch_array($result2)){
															if($row2[0] == $row0[2]){
																echo "<option selected value = ".$row2[0].">".$row2[1]."</option>";
															}
															else echo "<option value = ".$row2[0].">".$row2[1]."</option>";
														}
														
														if($row0[2] == ""){
    												        echo "<option selected value = 'NULL'>Нет принадлежности</option>";
    												    }
    												    else echo "<option value = 'NULL'>Нет принадлежности</option>";
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for = 'elem_3'>".$fn[3].":</label></td>";
													echo "<td>";
												    echo "<select class = 'select_parent select_db' name = 'fn[]'>";
												        echo "<option value = 'NULL'>&nbsp;</option>";
												    switch ($row0[3]) {
												    	case '1':
												    		echo "<option selected value = '1'>Территория</option>";
															echo "<option value = '2'>Участок</option>";
															echo "<option value = '3'>Здание</option>";
															echo "<option value = '4'>Помещение и т.д.</option>";
												    		break;
												    	case '2':
												    		echo "<option value = '1'>Территория</option>";
															echo "<option selected value = '2'>Участок</option>";
															echo "<option value = '3'>Здание</option>";
															echo "<option value = '4'>Помещение и т.д.</option>";
												    		break;
												    	case '3':
												    		echo "<option value = '1'>Территория</option>";
															echo "<option value = '2'>Участок</option>";
															echo "<option selected value = '3'>Здание</option>";
															echo "<option value = '4'>Помещение и т.д.</option>";
												    		break;
												    	case '4':
												    		echo "<option value = '1'>Территория</option>";
															echo "<option value = '2'>Участок</option>";
															echo "<option value = '3'>Здание</option>";
															echo "<option selected value = '4'>Помещение и т.д.</option>";
												    		break;
												    }
													echo "</select>";
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for = 'elem_4'>".$fn[4].":</label></td>";
													echo "<td>";
														echo "<input type=text name = 'fn[]' value = '".$row0[4]."' required>";  
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for = 'elem_5'>".$fn[5].":</label></td>";
													echo "<td>";
														echo "<input type=text name = 'fn[]' value = '".$row0[5]."' required>";  
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for = 'elem_6'>".$fn[6].":</label></td>";
													echo "<td>";
														echo "<input type=text name = 'fn[]' value = '".$row0[6]."'>";  
													echo "</td>";
												echo "</tr>";

												echo "<tr>";
													echo "<td><label for = 'elem_7'>".$fn[7].":</label></td>";
													echo "<td>";
														while($row1 = mysqli_fetch_array($result1))
														{
															echo "<input hidden type = text name = 'fn[]' value = ".$row1[0].">";
															echo "<input disabled type = text value = '".$row1[1]."'>";
														}
													echo "</td>";
												echo "</tr>";
											}
										}
										else{
									    echo "<tr>";
												echo "<td><label for = 'elem_0'>".$fn[0].":</label></td>";
												echo "<td>";
												echo "<input type=text name = 'fn[]' required>"; 
												echo "</td>";
											echo "</tr>";
												
											echo "<tr>";
												echo "<td><label for = 'elem_1'>".$fn[1].":</label></td>";
												echo "<td>";
												echo "<input type=text name = 'fn[]' required>";  
												echo "</td>";
											echo "</tr>";

											// Вывод кадастровых номеров по id родителя
											//Переменная для вывода всех записей, в которых тип объекта меньше данного на единицу
											//$parent_id = intval($row0[2]) - 1;
											$parent_id =0;
											

											$query2  = "SELECT Objects.ObjectId, Objects.Name FROM Objects WHERE Objects.ObjectType = ".$parent_id." AND Objects.CompanyId = ".$company_admin['CompanyId'];
											$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link));
											echo "<tr>";
												echo "<td><label for = 'elem_2'>".$fn[2].":</label></td>";
												echo "<td>";
											    echo "<select class = 'select_child select_db' name = 'fn[]'>";
											        echo "<option value=NULL selected>Нет принадлежности</option>";
													echo "<option disabled>Выберите объект</option>";
												
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for = 'elem_3'>".$fn[3].":</label></td>";
												echo "<td>";
											    echo "<select class = 'select_parent select_db' name = 'fn[]'>";
											       
											    	echo "<option value = '1'>Территория</option>";
													echo "<option value = '2'>Участок</option>";
													echo "<option value = '3'>Здание</option>";
													echo "<option value = '4'>Помещение и т.д.</option>";
												echo "</select>";
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for = 'elem_4'>".$fn[4].":</label></td>";
												echo "<td>";
													echo "<input type=text name = 'fn[]' required>";  
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for = 'elem_5'>".$fn[5].":</label></td>";
												echo "<td>";
													echo "<input type=text name = 'fn[]' required>";  
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for = 'elem_6'>".$fn[6].":</label></td>";
												echo "<td>";
													echo "<input type=text name = 'fn[]'>";  
												echo "</td>";
											echo "</tr>";

											echo "<tr>";
												echo "<td><label for = 'elem_7'>".$fn[7].":</label></td>";
												echo "<td>";
												while($row1 = mysqli_fetch_array($result1))
												{
													echo "<input hidden type = text name = 'fn[]' value = ".$row1[0].">";
													echo "<input disabled type = text value = '".$row1[1]."'>";
												}
												echo "</td>";
											echo "</tr>";
										}
									?>
								</table>
							</div>
							<div>
							    <table width = '100%'>
								<tr>
									<td>
										<input type = "submit" class = "confirm" value = "Сохранить">
										<a href = '/ManagementCompany/?table=Objects'>Отменить</a>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
    									    	echo "<a href = '../index.php?table=Objects&ParentId = " . $res . "' target = '_blank'>Подчинённые объекты</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
    										
    										    echo "<a href = '../index.php?table=CommonDevices&ObjectId = " . $res . "' target = '_blank'>Общедомовые приборы учета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href = '../index.php?table=PersonalAccounts&ObjectId = " . $res . "' target = '_blank'>Лицевые счета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
    										echo "</td>";
										echo "</tr>";
										
										echo "<tr>";
										    echo "<td>";
										echo "<a href = '../index.php?table=AccountsQuery&CompanyId = " . $res . "' target = '_blank'>Запросы пользователей на добавление лицевых счетов</a>";
            									echo "&nbsp;&nbsp;&nbsp;";
            								echo "</td>";
										echo "</tr>";
									}
								?>
							    </table>
							</div>
							<input type = "hidden" name = "elem_table" value = "Objects">
						</form>
					</div>
			</div>
			
		</div>
	</div>

	<div class = "footer">
		<div class = "container"></div>
	</div>
</body>

<script>
	$(document).ready(function(){
		    
		    //Изменение содержимого компаний
			$('select.select_parent').on('change', function(){
			    var res;
				var val = $(this).val();
				var id = $("input[name = 'elem_id']").val()
				
				$('select.select_child').empty();
                $('select.select_child').prepend('<option disabled >Выберите объект</option>');
                $('select.select_child').prop('disabled', true);
				
                $.ajax({
                    method: "POST",
                    url: "/ManagementCompany/object_query.php",
                    data: { ObjectType: val, Elem_id: id },
                    dataType: 'JSON'
                })
                .done(function( response ) {
                    $('select.select_child').empty();
                    $('select.select_child').prepend('<option disabled>Выберите объект</option>');
                    
                    var len = response[0].length;
                    var keys = Object.values(response[0]);
                    var values = Object.values(response[1]);

                    for(var i=0; i<len; i++){
                        $('select.select_child').append('<option value = "' + keys[i] + '">' + values[i] + '</option>');
                    }
                    $('select.select_child').append('<option value = "NULL">Нет принадлежности</option>');
                    $('select.select_child').prop('disabled', false);
                });
            });
		})
</script>
</html>