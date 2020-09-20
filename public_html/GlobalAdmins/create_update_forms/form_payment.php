<?php
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
?>

<body>
	<?php
    	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    	if(count($_GET) !=0){
        	$accountid = $_GET["AccountId"];
    		$method = $_GET["Method"];
    		$date = $_GET["Date"];
    	}
    	else{
    	    $accountid = "-1";
    		$method = "-1";
    		$date = "-1";
    	}
		
		$fn = array("Лицевой счёт","Способ оплаты", "ФИО плательщика","Дата оплаты","Сумма оплаты ");
		$row1 = array(  
							  'AccountId'                => '',
							  'Method'                   => '1',
							  'PayerFio'                 => '',
							  'Date'                     => date( 'Y-m-d H:i:s' ),
							  'Summa'                    => '0');
	
        if(isset($accountid) and isset($method) and isset($date)){
            $query1 ="SELECT 
    						Payment.AccountId AS AccountId,
    						Payment.Method AS Method,
    						Payment.PayerFio AS PayerFio,
    						Payment.Date AS Date,
    						Payment.Summa AS Summa,
    						IFNULL(PersonalAccounts.Name,'') AS PersName
    						FROM Payment AS Payment 
    						LEFT JOIN PersonalAccounts AS PersonalAccounts ON (PersonalAccounts.AccountId = Payment.AccountId)
                            WHERE Payment.AccountId = ".$accountid." 
                            AND Method = ".$method."
                            AND Date = '".$date."'";
    
    		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
    		
    		if ( !$row1 = mysqli_fetch_array( $result1 ) ) {
    				$row1 = array(  
							  'AccountId'                => '',
							  'Method'                   => '1',
							  'PayerFio'                 => '',
							  'Date'                     => date( 'Y-m-d H:i:s' ),
							  'Summa'                    => '0');
            }
        }
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				<div class="container">
					<div class="content__title">
						<h5>Платежи</h5>
					</div>
					<form class="" method="post" action="/GlobalAdmins/create_update_query/update_query_payment.php">
 						<input type="hidden" name="elem_accid" value='<?php echo $accountid ?>'>
 						<input type="hidden" name="elem_date" value='<?php echo $date ?>'>
 						<input type="hidden" name="elem_method" value="<?php echo $method?>">
 						<div class="form_wrapper">
							<table>
								<tbody>
									<tr> 
										<td><label>Лицевой счёт: </label> </td>
										<td>
										    <select class='select_db' name="personalaccounts" required>
        										<?php
            										//echo "<option value = 'NULL'>Не указано</option>";
            										$query = "SELECT 
            										            PersonalAccounts.AccountId AS AccountId, 
            										            PersonalAccounts.Name AS Name 
            										            FROM PersonalAccounts";
            										$result_sel = mysqli_query($link, $query);
            										
            										while ($row = mysqli_fetch_array($result_sel)) 
            										{	
            											if($row['AccountId']==$accountid){
            												echo "<option selected value = ".$row['AccountId'].">".$row['Name']."</option> ";
            											}
            											else{
            												echo "<option value = ".$row['AccountId'].">".$row['Name']." </option> ";
            											}								
            										} 															
            									?>
								            </select>
							            </td>
						            </tr>
        							<tr> 
        								<td><label>Способ оплаты: </label> </td>

										<td>
										    <select class='select_db' name="method" required>
										        <?php       
        											if($row1['Method'] == 1)
        												{
        													echo "<option selected value = '1'>1</option>";
										                    echo "<option value = '2'>2</option>";
            											}
            										else
            											{
            												echo "<option value = '1'>1</option>";
										                    echo "<option selected value = '2'>2</option>";
            											}
        										?>
										    </select>
                                        </td>
        							</tr>
									<tr> 
										<td><label>ФИО плательщика: </label> </td>
										<td>
										<?php       
											echo "<input type=text name=payerfio value='".$row1['PayerFio']."' required>";
    											
										?>
									</tr>
									<tr> 
										<td><label>Дата оплаты: </label> </td>
										<td>
										<?php       
											echo "<input type=datetime-local id=start name=date value='" . date( 'Y-m-d\TH:i:s', strtotime( $row1['Date'] ) ) . "' required></td>";  
    											
										?>
										</td>
									</tr>
									<tr> 
										<td><label>Сумма оплаты: </label> </td>
										<td>
										<?php       
											echo "<input type=text name=summa value='".$row1['Summa']."' required>";
    											
										?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<input type="hidden" name="elem_table" value="Payment">
						<input type="submit" class="confirm" value="Сохранить">
						<?php 
							echo "<a href='/GlobalAdmins/?table=Payment'>Отменить</a>";
						?>
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

