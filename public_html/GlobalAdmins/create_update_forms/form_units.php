<?php
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
?>

<html>
	<body>
		<?php
		    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		    
			$res = $_GET["UnitId"];
			$fn  = $names_ar[ 'Units' ];
			
			if($res != -1){
			    $query1 = "SELECT * FROM Units WHERE UnitId=".$res;
    		    $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    		    if ( !$row = mysqli_fetch_array( $result1 ) ) {
				$row = array( 'UnitId'                => -1, 
							  'Name'                  =>'');
			    }
			}
			
																
		?>
		
		<div class = "main">
			<div class = "container content">
				<div class = "main__left">
					
					<div class = "container">
					
						<div class = "content__title">
							<h5>Единица измерения</h5>
						</div>
						
						<form class = "" method = "post" action = "/GlobalAdmins/create_update_query/update_query_units.php">
						
							<input type = "hidden" name = "elem_id" value='<?php echo $res?>'>
							
							<div class = "form_wrapper">
								<table>
									<tbody>
										<tr>
											<td><label>Наименование</label> </td>
											<td>
												<?php  
													if($res == -1){
														echo "<input type = text name = name value = '' required>";
													}
													else{
														echo "<input type =text name = name value = '".$row['Name']."' required>";
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
										<input type = "submit" class = "confirm" value = "Сохранить">
										<?php 
            								echo "<a href = '/GlobalAdmins/?table=Units'>Отменить</a>";
            							?>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
        										echo "<a href='../index.php?table=Services&UnitId=" . $res . "' target='_blank'>Коммунальные услуги</a>";
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