<?php
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
?>
<body>
	<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
		$res = $_POST["id"];
		$table = $_POST["table"];
		$column = $_POST["column"];
	?>


	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Contractor</h5>
						</div>
						<form class="" method="post" action="../create_update_delete/update_elem.php">
							<input type="hidden" name="elem_id" value='<?php echo $res?>'>
							<div class="form_wrapper">
								<table>
									<?php
										if ($res != -1){
											$query1 ="SELECT * FROM ".$table." WHERE ".$column." = " . $res;
											$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 

											$num_columns = $result1->field_count;

											while($row1 = mysqli_fetch_array($result1)){
												
												for ($j = 1; $j < count($_POST["fn"]); $j++) {
													echo "<tr>";
													echo "<td><label for='elem_".$j."'>".$_POST["fn"][$j].":</label></td>";
													echo "<td><input type='text' id='elem_".$j."' name='fn[]' value='".$row1[$j]."' required></td>";
													echo "</tr>";
												}
											}
										}
										else{

											for ($j = 1; $j < count($_POST["fn"]); $j++) {
												echo "<tr>";
												echo "<td><label for='elem_".$j."'>".$_POST["fn"][$j].":</label></td>";
												echo "<td><input type='text' id='elem_".$j."' name='fn[]' value='' required></td>";
												echo "</tr>";
											}
										}
									?>
								</table>
							</div>
							<input type="hidden" name="elem_table" value="<?php echo $table ?>">
							<input type="submit" class="confirm" value="Сохранить">
							<a href=" /GlobalAdmins/?table=<?php echo $table ?>">Отменить</a>
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