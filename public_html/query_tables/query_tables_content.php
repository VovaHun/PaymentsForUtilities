<table>
	<?php
		// вывод данных таблицы
		$query1 ="SELECT * FROM " . $active_table;
		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 

		$num_columns = $result1->field_count;

		$fn = $names_ar[$active_table];

		$query3 ="SHOW COLUMNS FROM " . $active_table;
		$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link)); 

        
		if($result3)
		{
		    $row3 = mysqli_fetch_array($result3);
		}
	?>
	<tbody>
		<tr>
			<?php
				foreach($fn as $nm)
					{
					    echo "<th>".$nm."</th>";
					}
			?>
			
			<td id='add_elem'>
				<form class="create_form" method="post" action="../create_update_delete/create_elem.php">
					<?php 
						
						foreach($fn as $val)
						{
						    echo "<input type=hidden name=fn[] value='".$val."'>";
						}
					?>
					<input type="hidden" name="id" value="-1">
					<input type="hidden" name="column" value="<?php echo $row3['0'] ?>">
					<input type="hidden" name="table" value="<?php echo $active_table ?>">
					<input class="form_submit_insert" type="submit" value="">
				</form>
			</td>
		</tr>

	<?php
		if($result1)
		{
		    while($row1 = mysqli_fetch_array($result1)){
		    	echo "<tr>";
				
				for ($i = 0; $i < $num_columns; $i++) {
					echo "<td>".($row1[$i])."</td>";
				}
	?>
	
				<td>
					<form class="update_form" method="post" action="../create_update_delete/create_elem.php">
						<?php 
							foreach($fn as $val)
							{
								echo "<input type=hidden name=fn[] value='".$val."'>";
							}
						?>
						<input type="hidden" name="id" value="<?php echo $row1[0] ?>">
						<input type="hidden" name="column" value="<?php echo $row3['0'] ?>">
						<input type="hidden" name="table" value="<?php echo $active_table ?>">
						<input class="form_submit_edit" type="submit" value="">
					</form>
				</td>
				<td><a a href="#ex1" onclick="Delete('<?php echo $active_table ?>', '<?php echo $row1[0] ?>')" rel="modal:open" ><img src="../images/Delete.png" alt=""></a></td>
				
				</tr>
	<?php
			}
		}
	?>
	</tbody>
</table>