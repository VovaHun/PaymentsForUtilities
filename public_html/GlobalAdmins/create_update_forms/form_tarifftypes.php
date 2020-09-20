<?php
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
?>

<html>
	<body>
		<?php
		    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		   
			$res = $_GET["TariffId"];
				$row = array( 'TariffId'              => -1, 
							  'Name'                  => '',
							  'IsNormative'           => '');
			if($res != -1 ){
			    	$query1 = "SELECT  TariffTypes.TariffId AS TariffId, TariffTypes.Name AS Name, TariffTypes.IsNormative AS IsNormative FROM TariffTypes WHERE TariffTypes.TariffId =".$res;
    		        $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    		  if ( !$row = mysqli_fetch_array( $result1 ) ) {
				$row = array( 'TariffId'              => -1, 
							  'Name'                  => '',
							  'IsNormative'           => '');
							  
			}
		}
		

		
																
		?>
		
		<div class = "main">
			<div class = "container content">
				<div class = "main__left">
					
					<div class = "container">
					
						<div class = "content__title">
							<h5>Вид тарифа</h5>
						</div>
						
						<form class = "" method = "post" action = "/GlobalAdmins/create_update_query/update_query_tarifftypes.php">
						
							<input type = "hidden" name = "elem_id" value='<?php echo $res?>'>
							
							<div class = "form_wrapper">
							
								<table>
									<tbody>
										<tr>
											<td><label>Наименование</label> </td>
											<td>
												<?php  
													if($res == -1){
														echo "<input type =text name =name value = '' required>";
													}
													else{
														echo "<input type =text name =name value = '$row[1]' required>";
													}

												?>
											</td>
										</tr>
										
										<tr>
											<td><label>Является нормативом</label> </td>
											<td>
											<?php
										          echo "<input type=checkbox " . ( ( $row[ 'IsNormative' ] == 1 ) ? "checked " : "" ) . "name=normative id='normative'>";
													
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
            								echo "<a href = '/GlobalAdmins/?table=TariffTypes'>Отменить</a>";
            							?>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
        										echo "<a href='../index.php?table=Tariffs&TariffId=" . $res . "' target='_blank'>Тарифы</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=AccountServices&TariffId=" . $res . "' target='_blank'>Состав коммунальных услуг</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        								    echo "</td>";
    									echo "</tr>";
        								echo "<tr>";
    										echo "<td>";
        									    echo "<a href='../index.php?table=CommonDevices&TariffId=" . $res . "' target='_blank'>Общедомовые приборы учета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        									
        										echo "<a href='../index.php?table=AccountDevices&TariffId=" . $res . "' target='_blank'>Индивидуальные приборы учёта</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=AccountNormatives&TariffId=" . $res . "' target='_blank'>Нормативы по лицевым счетам</a>";
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
</html>