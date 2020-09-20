<?php
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    
?>

<body>
	<?php
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	
		$res = $_GET["ServiceId"];
		$fn  = $names_ar[ 'Services' ];
		$row0 = array( 'ServiceId'                   => -1, 
							  'Name'                 => '',
							  'СontractorId'         => '',
							  'IsComposite'          => 0,
							  'MainServiceId'        => '',
							  'UnitId'               => '',
							  'IsPrint'             => 0);
		if($res != -1){
		    	$query0 ="SELECT 
							Services.ServiceId AS ServiceId,
							Services.Name AS Name,
							Services.СontractorId AS СontractorId,
							Services.CompanyId AS CompanyId,
							Services.IsComposite AS IsComposite,
							Services.MainServiceId AS MainServiceId,
							Services.UnitId AS UnitId,
							Services.IsPrint AS IsPrint
							FROM Services AS Services WHERE ServiceId = ".$res;
		$result0 = mysqli_query($link, $query0) or die("Ошибка " . mysqli_error($link));
		
		if ( !$row0 = mysqli_fetch_array(  $result0 ) ) {
				$row0 = array( 'ServiceId'            => -1, 
							  'Name'                 => '',
							  'СontractorId'         => '',
							  'IsComposite'          => 0,
							  'MainServiceId'        => '',
							  'UnitId'               => '',
							  'IsPrint'             => 0);
							 
		        }
		}
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				<div class="container">
					<div class="content__title">
						<h5>Коммунальная услуга</h5>
					</div>
					<form class="" method="post" action="/GlobalAdmins/create_update_query/update_query_services.php">
						<input type="hidden" name="elem_id" value='<?php echo $res?>'>
						<div class="form_wrapper">
							<table>
								<tbody>
									<tr> 
										<td><label>Наименование услуги: </label> </td>
										<td>
										<?php       
											if($res == -1)
											{ 
												echo "<input type=text name=Name value='' required>";  
											}
											else
											{
												echo "<input type=text name=Name value='".$row0['Name']."' required>";  
											}
										?>
										</td>
									</tr>
					
									<tr> 
										<td><label>Поставщик комунальных услуг: </label></td>
										<td><select class="select_contractor select_db" name="contractor">
											<?php
											    echo "<option value = 'NULL'>&nbsp;</option>";
											    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
												$query1= "SELECT Contractors.СontractorId, Contractors.Name FROM Contractors ORDER BY Contractors.Name";
												$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
												while ($row1 = mysqli_fetch_array($result1))
												{
													if($row1[0] == $row0['СontractorId'])
													{ 
														echo "<option selected value = $row1[0]>$row1[1]</option> ";
														$selectedContractor = $row1[0];
														
													}
												else
													{
														echo "<option  value = $row1[0]>$row1[1]</option> ";  
													}
												}
											?>
										</select>
										</td>
									</tr>
									<tr> 
										<td><label>Управляющая компания: </label></td>
										<td><select class='select_db' name="company">
											<?php
											    echo "<option value = 'NULL'>&nbsp;</option>";
											    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
												$query2= "SELECT ManagementCompany.CompanyId, ManagementCompany.Name FROM ManagementCompany ORDER BY ManagementCompany.Name";
												$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link)); 
												while ($row2 = mysqli_fetch_array($result2))
												{
													if($row2[0] == $row0['CompanyId'])
													{ 
														echo "<option selected value = $row2[0]>$row2[1]</option> ";
													}
												else
													{
														echo "<option  value = $row2[0]>$row2[1]</option> ";  
													}
												}
												
											?>
										</select>
										</td>
									</tr>
									<tr> 
										<td><label>Составная услуга: </label></td>
										<td>
										    <?php
										    
										        echo "<input type=checkbox " .(( $row0['IsComposite'] == 1 ) ? "checked " : "" )." name=compose class=select_compose id=check>";
										    ?>
										</td>
									</tr>
									<tr> 
										<td><label>Входит в состав услуги: </label></td>
										<td>
											<select class="select_mainserv select_db" name="mainServ">
												<?php 
													if($res==-1){
														echo "<option value='NULL'>Не входит</option>";
													}  
													elseif ($res != -1 AND $row0['IsComposite'] == 0) {
														echo "<option value='NULL'>Не входит</option>";
													}
													else
													{
													    $query3= "SELECT Services.ServiceId, Services.Name FROM Services WHERE Services.ServiceId != ".$res." AND Services.СontractorId = ".$selectedContractor." ORDER BY Services.Name";
														$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link)); 
														
														while ($row3 = mysqli_fetch_array($result3)){
    														if ($row3['ServiceId'] == $row0['MainServiceId']) {
    															echo "<option selected value='".$row3[0]."'>".$row3[1]."</option>";
    														}
    														else {
    													        echo "<option value='".$row3[0]."'>".$row3[1]."</option>";
    														}
														    
														}
														
													}						
												?>
											</select>
										</td>
									</tr> 
									<tr> 
										<td><label>Единица измерения: </label></td>
										<td>
											<select class='select_db' name="unit">
												<?php
												   
													$query4= "SELECT * FROM Units ORDER BY Units.Name";
													$result4 = mysqli_query($link, $query4) or die("Ошибка " . mysqli_error($link)); 
													while ($row4 = mysqli_fetch_array($result4))
													{

													 	if($row4[0]==$row0['UnitId'])
														{ 
															echo "<option selected value = $row4[0]>$row4[1]</option> "; 
														}
														else
														{
															echo "<option  value = $row4[0]>$row4[1]</option> ";  
														}
													}									 							
												?>
											</select>
										</td>
									</tr>
						
									<tr> <td><label>Печать:</label></td>
										<td>
											<?php 
											
											  echo "<input type=checkbox " .(( $row0['IsPrint'] == 1 ) ? "checked " : "" )." name=print  id=print>";
											 
											
											?>
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
                    						echo "<a href='/GlobalAdmins/?table=Services'>Отменить</a>";
                    					?>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
        										echo "<a href='../index.php?table=AccountServices&ServiceId=" . $res . "' target='_blank'>Состав коммунальных услуг</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=CommonDevices&ServiceId=" . $res . "' target='_blank'>Общедомовые приборы учета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=AccountDevices&ServiceId=" . $res . "' target='_blank'>Индивидуальные приборы учёта</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
    										echo "</td>";
										echo "</tr>";
										
										echo "<tr>";
    										echo "<td>";
        				                        echo "<a href='../index.php?table=AccountNormatives&ServiceId=" . $res . "' target='_blank'>Нормативы по лицевым счетам</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=Tariffs&ServiceId=" . $res . "' target='_blank'>Тарифы</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=Services&MainServiceId=" . $res . "' target='_blank'>Подчиненные комунальные услуги</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
    										echo "</td>";
										echo "</tr>";
									}
								?>
							    </table>
							</div>
					<input type="hidden" name="elem_table" value="Services">
					</form>
				</div>
			</div>	
		</div>
	</div>

	<div class="footer">
		<div class="container"></div>
	</div>
</body>


<script>
	$(document).ready(function(){  
	    //Изменение содержимого компаний
	    $('select.select_contractor').on('change', function(){
	    	$("#check").prop( "checked", false );

            $('select.select_mainserv').empty();
            $('select.select_mainserv').prepend('<option selected value = "NULL">Не входит</option>');

	    });
        
        $('#check').click(function(){
	        if($("#check").prop("checked")){
	            var res;
				var id = $("input[name='elem_id']").val();
				var contractorid = $('select.select_contractor').val();

	            $.ajax({
	                method: "POST",
	                url: "/GlobalAdmins/services_query.php",
	                data: { ServiceId: id, ContractorId: contractorid },
                    dataType: 'JSON'
	            })
	            .done(function( response ) {
	                
	                var len = response[0].length;
	                var keys = Object.values(response[0]);
	                var values = Object.values(response[1]);

	                for(var i=0; i<len; i++){
	                    $('select.select_mainserv').append('<option value="' + keys[i] + '">' + values[i] + '</option>');
	                }
	            });
	        }
	        else{
	            $('select.select_mainserv').empty();
	            $('select.select_mainserv').prepend('<option selected value = "NULL">Не входит</option>');
	        }
        });    
	})
</script>

</html>

