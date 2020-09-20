<div class = "wrapper_left">
    <table id = "data_table">
		<tbody>
    		<tr id = 'tr_parent_0'>
				<?php
					$fn = array("Администратор", "Логин", "Пароль", "Имя", "Email", "Телефон", "Комментарий");
				
					foreach($fn as $nm)
					{
						echo "<th>".$nm."</th>";
					}
				?>
			</tr>
	
			<?php
				// Вывод данных из таблиц
				$query1 = "SELECT 
								GlobalAdmins.GlobalAdminId AS GlobalAdminId, 
								GlobalAdmins.Login AS Login, 
								GlobalAdmins.Password AS Password, 
								GlobalAdmins.Name AS AdminName, 
								GlobalAdmins.Email AS Email, 
								GlobalAdmins.Phone AS Phone, 
								GlobalAdmins.Comment AS Comment 
								FROM GlobalAdmins 
								ORDER BY Login";
				
				$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
				$ct		 = 1;

				if($result1)
				{
					while($row1 = mysqli_fetch_array($result1))
					{
						echo "<tr id='tr_parent_" .$ct. "'>";
						echo "<td>".$row1['GlobalAdminId']."</td>";
						echo "<td>".$row1['Login']."</td>";
						echo "<td>".$row1['Password']."</td>";
						echo "<td>".$row1['AdminName']."</td>";
						echo "<td>".$row1['Email']."</td>";
						echo "<td>".$row1['Phone']."</td>";
						echo "<td>".$row1['Comment']."</td>";
						echo "</tr>";
						
						$data[] = $row1[ 'GlobalAdminId' ];
						$ct     = $ct + 1;
					}
				}
			?>
    	</tbody>
    </table>
</div>

<div class = "table_buttons" style = "width: 10%">
    <table>
        <tr id = 'tr_child_0'>
            <td id = 'add_elem' width = '20px'><a href = './create_update_forms/form_globaladmins.php?GlobalAdminId=-1'><img src = '../images/Insert.png' alt = 'Добавить запись'></a></td>
    	</tr>
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id = 'tr_child_" .$ct. "'>";
					echo "<td width = '20px'><a href = './create_update_forms/form_globaladmins.php?GlobalAdminId=" . $value . "'><img src = '../images/Edit.png' alt = 'Изменить запись'></a></td>";
					echo "<td width = '20px'><a href = '#ex1' onclick=\"Delete('GlobalAdmins', " . $value . ")\" rel = 'modal:open'><img src = '../images/Delete.png' alt = 'Удалить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>