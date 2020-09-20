<?php
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
?>

<body>
	<?php
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

		$res = $_GET["ModelId"];
		$fn  = $names_ar[ 'DeviceModels' ];
		if($res != -1){
		    	$query1 ="SELECT 
		    	    DeviceModels.ModelId AS ModelId,
		    	    DeviceModels.Name AS Name,
		    	    DeviceModels.CheckInterval AS CheckInterval,
		    	    DeviceModels.Maker AS Maker
		    	    FROM `DeviceModels` WHERE DeviceModels.ModelId = ".$res." GROUP BY DeviceModels.Name";
        		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
		    if ( !$row1 = mysqli_fetch_array( $result1 ) ) {
				$row1 = array( 'ModelId'                 => -1, 
							  'Name'                     => '',
							  'CheckInterval'            => '',
							  'Maker'                    => '');
							 
        	}
		    
		}
	

															
	?>

	

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Модель прибора учета</h5>
						</div>
						<form class="" method="post" action="/GlobalAdmins/create_update_query/update_query_devicemodels.php">
 							<input type="hidden" name="elem_id" value='<?php echo $res?>'>
 							<div class="form_wrapper">
								<table>
								
									<tbody>
									<tr> 
										<td><label>Наименование модели </label> </td>
										<td>
										<?php       
										if($res == -1)
											{ 
												echo "<input type=text name=deviceName value='' required>";  
    										}
    									else
    										{
    											echo "<input type=text name=deviceName value='".$row1['Name']."' required>";  
    										}

										?>
								</td>
							</tr>
			
							<tr> 
								<td><label>Межповерочный интервал, мес: </label> </td>
									<td>
										<?php       
										if($res == -1)
											{ 
												echo "<input type=text name=dateInt value='' required>";  
    										}
    									else
    										{
    											echo "<input type=text name=dateInt value='".$row1['CheckInterval']."' required>";  
    										}

										?>

									</tr>
									<tr> 
										<td><label>Производитель: </label> </td>
										<td>
										<?php       
											if($res == -1)
												{ 
													echo "<input type=text name=creator value='' required>";  
    											}
    										else
    											{
    												echo "<input type=text name=creator value='".$row1['Maker']."' required>";  
    											}

										?>
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
            								echo "<a href='/GlobalAdmins/?table=DeviceModels'>Отменить</a>";
            							?>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
        										echo "<a href='../index.php?table=ModelFunctions&ModelId=" . $res . "' target='_blank'>Назначение моделей приборов учета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										echo "<a href='../index.php?table=Devices&ModelId=" . $res . "' target='_blank'>Приборы учета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
    										echo "</td>";
										echo "</tr>";
									}
								?>
							</table>
							</div>
							<input type="hidden" name="elem_table" value="DeviceModels">
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

