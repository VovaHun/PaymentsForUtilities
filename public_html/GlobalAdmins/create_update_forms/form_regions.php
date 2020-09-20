<?php
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
?>

<html>
	<body>
		<?php
		    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		    
			$res = $_GET["RegionId"];
			$fn  = $names_ar[ 'Regions' ];
			$row1 = array( 'RegionId'             => -1, 
							  'RegionCode'            => '',
							  'Name'                  => '');
			if($res != -1){
			     $query1 ="SELECT * FROM Regions WHERE RegionId=".$res;
    		    $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
    		    if ( !$row1 = mysqli_fetch_array( $result1 ) ) {
				$row1 = array( 'RegionId'             => -1, 
							  'RegionCode'            => '',
							  'Name'                  => '');
							  
			}
		    
    		
		}	
																
		?>
		
		<div class = "main">
			<div class = "container content">
				<div class = "main__left">
					
					<div class = "container">
					
						<div class = "content__title">
							<h5>Регионы</h5>
						</div>
						
						<form class = "" method = "post" action = "/GlobalAdmins/create_update_query/update_query_regions.php">
						
							<input type = "hidden" name = "elem_id" value='<?php echo $res?>'>
							
							<div class = "form_wrapper">
								<table>
									<tbody>
									    <tr>
											<td><label>Код региона</label> </td>
											<td><?php  
													if($res == -1){
														echo "<input type = text name = code value = '' required>";
													}
													else{
														echo "<input type =text name = code value = '$row1[1]' required>";
													}

												?>
												
											</td>
										</tr>
										<tr>
											<td><label>Наименование</label> </td>
											<td><?php  
													if($res == -1){
														echo "<input type = text name = name value = '' required>";
													}
													else{
														echo "<input type =text name = name value = '$row1[2]' required>";
													}

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
										<a href="/GlobalAdmins/?table=Regions">Отменить</a>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
        										echo "<a href='../index.php?table=Contractors&RegionId=" . $res . "' target='_blank'>Поставщики коммунальных услуг</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=ManagementCompany&RegionId=" . $res . "' target='_blank'>Управляющие компании</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=Tariffs&RegionId=" . $res . "' target='_blank'>Тарифы</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        							
    										echo "</td>";
										echo "</tr>";
									}
								?>
							    </table>
							</div>
							<input type="hidden" name="elem_table" value="<?php echo $table ?>">
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>