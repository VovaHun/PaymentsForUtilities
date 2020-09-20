<div class = "wrapper">
    <table id = "data_table">
        <tbody>
            <tr id = 'tr_parent_0'>
			
			<?php
				// Вывод данных из таблиц
				$query1 = "SELECT 
								AccountsQuery.QueryId AS QueryId, 
								AccountsQuery.AccountName AS AccountName, 
								Users.Name AS UsersName, 
								Objects.Address AS ObjectsName, 
								ManagementCompany.Name AS ManageName,
								AccountsQuery.QueryStatus AS QueryStatus,
								AccountsQuery.QueryDate AS QueryDate
								FROM AccountsQuery
								LEFT JOIN Users AS Users ON (Users.UserId = AccountsQuery.UserId)
								LEFT JOIN Objects AS Objects ON (Objects.ObjectId = AccountsQuery.ObjectId)
								LEFT JOIN ManagementCompany AS ManagementCompany ON (ManagementCompany.CompanyId = AccountsQuery.CompanyId)";

				$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
		
				$fn = array("Запрос", "Номер лицевого счёта", "Пользователь, отправивший запрос", "Управляющая компания", "Объект недвижимости", "Статус запроса", "Дата и время запроса");

				foreach($fn as $nm)
				{
					echo "<th>".$nm."</th>";
				}
			?>	
	
			</tr>
			
			<?php
				if($result1)
				{
					while($row1 = mysqli_fetch_array($result1))
					{
						echo "<tr id = '" . $row1[ 'QueryId' ] . "'>";
						echo "<td>".$row1['Name']."</td>";
						echo "<td>".$row1['UsersName']."</td>";
						echo "<td>".$row1['ManageName']."</td>";
						echo "<td>".$row1['ObjectsName']."</td>";
						echo "<td>".$row1['QueryStatus']."</td>";
						echo "<td>".$row1['QueryDate']."</td>";
						echo "</tr>";
					}
				}
			?>
    	</tbody>
    </table>
</div>