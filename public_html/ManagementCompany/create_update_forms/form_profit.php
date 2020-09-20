<?php
	require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
	require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
?>

<body>
	<?php
	    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	
		/*$res = $_GET["ModelId"];
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
		}*/
	?>
	
	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Расчёт начисления</h5>
						</div>
						<form class = "" method = "post" action = "/ManagementCompany/create_update_query/update_query_profit.php">
 							<input type="hidden" name="elem_id" value='<?php echo $res?>'>
 							<div class="form_wrapper">
								<table>
									<tbody>
    									<tr> 
    										<td><label>Управляющая компания</label> </td>
    										<td>
    										    <select class = 'select_db' name='company' required>
            										<?php       
                    									$query = "SELECT ManagementCompany.CompanyId, ManagementCompany.Name FROM ManagementCompany WHERE ManagementCompany.CompanyId = '".$company_admin['CompanyId']."' ";
														$result_sel = mysqli_query($link, $query);
														
                                                        while ($row = mysqli_fetch_array($result_sel)){
                                                            echo "<option value = ".$row['CompanyId'].">".$row['Name']." </option> ";
                                                        }
            										?>
        										</select>
            								</td>
            							</tr>

            							<tr> 
            								<td><label>Период</label> </td>
        									<td>
        										<?php
        										    echo "<input type=datetime-local id=start name=date value='".date( 'Y-m-d\TH:i:s')."' ></td>"; 
        										    
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
            								echo "<a href='/ManagementCompany/?table=Profit'>Отменить</a>";
            							?>
									</td>
								</tr>
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

