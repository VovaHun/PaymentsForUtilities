<div class = "title_wrapper">
	<div class = "content_title">
		<h5>Запрос</h5>
	</div>
</div>

<div class = "">
	<?php
	    $query_id = $_GET['personalId'];
        $sql_query = "SELECT AccountsQuery.QueryStatus AS QueryStatus, 
					AccountsQuery.QueryDate AS QueryDate, 
					AccountsQuery.AccountName AS AccountName, 
					ManagementCompany.FullName AS CompanyName, 
					Objects.Name AS ObjectName, 
					Objects.KadastrNo AS KadastrNo, 
					AccountsQuery.QueryAnswer AS QueryAnswer
					FROM AccountsQuery
					LEFT JOIN ManagementCompany ON (ManagementCompany.CompanyId = AccountsQuery.CompanyId)
					LEFT JOIN Objects ON (Objects.ObjectId = AccountsQuery.ObjectId)
					WHERE AccountsQuery.UserId = ".$user['UserId']." 
					AND AccountsQuery.QueryId = ".$query_id." ";

	     $query = mysqli_query($link, $sql_query) or die("Ошибка " . mysqli_error($link));
		
		$result = mysqli_fetch_assoc($query);
	    
	?>
	<div class = "form_wrapper">
		<table>
			<tbody>
				<tr>
					<td>
						<label>Статус запроса</label>
					</td>
					
					<td>
						<?php   
							if($result['QueryStatus'] == 0)
							{
								echo "<input disabled type = text name = 'queryStatus' value = 'На рассмотрении'>";	
							}
							else if ($result['QueryStatus'] == 2)
							{
								echo "<input disabled type = text name = 'queryStatus' value = 'Не одобрен'>";	
							}
						?>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Дата запроса</label>
					</td>
					
					<td>
						<?php   
							echo "<input disabled type = text name = 'queryDate' value = '".$result['QueryDate']."'>";	
						?>
					</td>
				</tr>
				
				<tr>
					<td>
						<!--<label>Номер лицевого счёта</label>-->
						<label>Данные запроса</label>
					</td>
					
					<td>
						<?php   
							echo "<input disabled type = text name = 'accountName' value = '".$result['AccountName']."'>";	
						?>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Управляющая компания</label>
					</td>
					
					<td>
						<?php   
							echo "<input disabled type = text name = 'companyName' value = '".$result['CompanyName']."'>";	
						?>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Объект</label>
					</td>
					
					<td>
						<?php   
							echo "<input disabled type = text name = 'objectInfo' value = '".$result['ObjectName']." (".$result['KadastrNo'].")'>";	
						?>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Комментарий администратора УК</label>
					</td>
					
					<td>
						<?php   
							echo "<input disabled type = text name = 'queryAnswer' value = '".$result['QueryAnswer']."' style = 'height: 100px'>";	
						?>
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>