<div class="title_wrapper">
					
	<div class="content__title">
		<h5>Лицевые счета</h5>
	</div>
	
</div>

<div class = "wrapper_left">
	
	<?php
		// Вывод данных из таблиц
		$query1 = "SELECT PersonalAccounts.Name, Objects.Name, Objects.Address, Objects.ObjectType, Objects.Square, ManagementCompany.Name FROM PersonalAccounts, Objects, ManagementCompany, AccountUsers WHERE PersonalAccounts.AccountId = AccountUsers.AccountId AND AccountUsers.UserId = '".$user['UserId']."' AND PersonalAccounts.ObjectId = Objects.ObjectId AND PersonalAccounts.CompanyId = ManagementCompany.CompanyId GROUP BY PersonalAccounts.AccountId";
		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
		
		// Добавить новую переменную в сессию: ссылка на Id лицевого счёта или на абонента
		
		$num_columns = $result1->field_count;

		$fn = $names_ar['PersonalAccounts'];

		$query2 = "SHOW COLUMNS FROM `PersonalAccounts`";
		$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link)); 

		if($result2)
		{
			$row2 = mysqli_fetch_array($result2);
		}
		$ct = 1;
		
		$count = mysqli_num_rows($result1);
		
		if ($count > 0)
		{
			echo "<table id = 'data_table'>";
		
			echo "<tbody>";
			
			echo "<tr id = 'tr_parent_0'>";
			
			echo "<th>Номер лицевого счёта</th>";
			echo "<th>Кадастровый номер</th>";
			//<th>Регион</th>
			echo "<th>Адрес</th>";
			echo "<th>Тип объекта</th>";
			echo "<th>Площадь</th>";
			echo "<th>Управляющая компания</th>";
			echo "</tr>";
							
			while($row1 = mysqli_fetch_array($result1))
			{
				echo "<tr id = 'tr_parent_".$ct."'>";
				$ct = $ct + 1;
				
				for ($i = 0; $i < $num_columns; $i++) 
				{
					echo "<td>".($row1[$i])."</td>";
				}
				
				echo "</tr>";
			}
			
			echo "</tbody>";
			
			echo "</table>";
		}
	?>
	
	<div style = "margin: 10px; weight: 100%;">
	
		<a href = "/create_elem_personalaccount.php">Добавить новый персональный счёт</a>
	
	</div>
	
</div>
