<?php
    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_header.php';
?>

<body>
	<?php
	    require $_SERVER['DOCUMENT_ROOT'].'/ManagementCompany/_admin_header.php';
	    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	    	$row = array( 'AbonentId'                => -1, 
							  'AbonentType'              => '',
							  'Name'                     => '',
							  'LegalAddress'             => '',
							  'ActualAddress'            => '',
							  'Email'                    => '',
							  'Phone'                    => '',
							  'INN'                      => '',
							  'KPP'                      => '',
							  'OGRN'                     => '',
							  'DateOfBirth'              => '',
							  'PositionHead'             => '',
							  'FIO'                       => '',
							  'PaymentAccount'            => '',
							  'Bank'                      => '',
							  'BIK'                       => '',
							  'CorrespondentAccount'      => '',
							  'ConcentOnPersonalData'     => 0);
	    
		$res = $_GET["AbonentId"];
		//$table = $_POST["table"];
		
		if ( $res != -1 ) {
			mysqli_report( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
			$query  = "SELECT 
						   Abonents.AbonentId AS AbonentId,
						   Abonents.AbonentType AS AbonentType,
						   Abonents.Name AS Name,
						   Abonents.LegalAddress AS LegalAddress,
						   Abonents.ActualAddress AS ActualAddress,
						   Abonents.Email AS Email,
						   Abonents.Phone AS Phone,
						   Abonents.INN AS INN,
						   Abonents.KPP AS KPP,
						   Abonents.OGRN AS OGRN,
						   Abonents.DateOfBirth AS DateOfBirth,
						   Abonents.PositionHead AS PositionHead,
						   Abonents.FIO AS FIO,
						   Abonents.PaymentAccount AS PaymentAccount,
					       Abonents.Bank AS Bank,
					       Abonents.BIK AS BIK,
					       Abonents.CorrespondentAccount AS CorrespondentAccount,
					       Abonents.ConcentOnPersonalData AS ConcentOnPersonalData
					   FROM Abonents AS Abonents 
					   WHERE ( Abonents.AbonentId = " . $res . " )
					   LIMIT 1";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );
			
			if ( !$row = mysqli_fetch_array( $result ) ) {
				$row = array( 'AbonentId'                => -1, 
							  'AbonentType'              => '',
							  'Name'                     => '',
							  'LegalAddress'             => '',
							  'ActualAddress'            => '',
							  'Email'                    => '',
							  'Phone'                    => '',
							  'INN'                      => '',
							  'KPP'                      => '',
							  'OGRN'                     => '',
							  'DateOfBirth'              => '',
							  'PositionHead'             => '',
							  'FIO'                       => '',
							  'PaymentAccount'            => '',
							  'Bank'                      => '',
							  'BIK'                       => '',
							  'CorrespondentAccount'      => '',
							  'ConcentOnPersonalData'     => 0);
			}
		}
	?>

	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Абонент</h5>
						</div>
						<form class="" method="post" action="/ManagementCompany/create_update_query/update_query_abonents.php">
 						<input type="hidden" name="elem_id" value='<?php echo $res?>'>
 						<div class="form_wrapper">
							<table>
								<tbody>
									<tr>
										<td><label>Тип абонента:</label></td>
										<td><select name="type">
											<?php 
											if($res==-1){
												echo "<option  value = 1>Физическое лицо</option> ";
												echo "<option  value = 2>Юридическое лицо</option> ";  
											}
											else
											{
												if($row['AbonentType']==1){
												echo "<option selected value = 1>Физическое лицо</option> ";
												echo "<option  value = 2>Юридическое лицо</option> ";
												}
												else {
												echo "<option  value = 1>Физическое лицо</option> ";
												echo "<option selected  value = 2>Юридическое лицо</option> ";
												}
											}

											?>
										</select></td>
									</tr>

									<tr> <td><label>Наименование (ФИО):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=Name value = > ";	
											}
											else{
												echo "<input type=text name=Name value = '".$row['Name']."'> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>Юридический адрес или адрес регистрации: </label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=adres value = > ";	
											}
											else{
												echo "<input type=text name=adres value = '".$row['LegalAddress']."'> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>Фактический адрес или адрес проживания:</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=adres2 value = > ";	
											}
											else{
												echo "<input type=text name=adres2 value = '".$row['ActualAddress']."'> ";
											}
											?>
										</td>								
									</tr>
										<tr> <td><label>Электронная почта:</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=email value = > ";	
											}
											else{
												echo "<input type=text name=email value = '".$row['Email']."'> ";
											}
											?>
										</td>								
									</tr>
									</tr>
										<tr> <td><label>Телефон:</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=phone value = > ";	
											}
											else{
												echo "<input type=text name=phone value = '".$row['Phone']."'> ";
											}
											?>
										</td>								
									</tr>
									</tr>
										<tr> <td><label>ИНН:</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text pattern='^[ 0-9]+$' maxlength=12 name=inn value = > ";	
											}
											else{
												echo "<input type=text pattern='^[ 0-9]+$' maxlength=12 name=inn value = '".$row['INN']."'> ";
											}
											?>
										</td>								
									</tr>
									
									<tr> <td><label>КПП (для юр.лиц):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text pattern='^[ 0-9]+$' maxlength=9 name=kpp value = > ";	
											}
											else{
												echo "<input type=text pattern='^[ 0-9]+$' maxlength=9 name=kpp value = '".$row['KPP']."'> ";
											}
											?>
										</td>								
									</tr>

									<tr> <td><label>ОГРН (для юр.лиц):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text pattern='^[ 0-9]+$' name=orgn value = > ";	
											}
											else{
												echo "<input type=text pattern='^[ 0-9]+$' name=orgn value = '".$row['OGRN']."'> ";
											}
											?>
										</td>								
									</tr>

									<tr> <td><label>Дата рождения (физ. лица):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=date name=datebith value = > ";	
											}
											else{
												echo "<input type=date name=datebith value = ".$row['DateOfBirth']."> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>Должность руководителя (юр.лица):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=head value = > ";	
											}
											else{
												echo "<input type=text name=head value = '".$row['PositionHead']."'> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>ФИО руководителя (юр.лица):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=headName value = > ";	
											}
											else{
												echo "<input type=text name=headName value = '".$row['FIO']."'> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>Расчетный счет (юр.лица):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=payAc value = > ";	
											}
											else{
												echo "<input type=text name=payAc value = '".$row['PaymentAccount']."'> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>Банк (юр.лица):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=bank value = > ";	
											}
											else{
												echo "<input type=text name=bank value = '".$row['Bank']."'> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>БИК (юр.лица):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text pattern='^[ 0-9]+$' maxlength=9 name=bik value = > ";	
											}
											else{
												echo "<input type=text pattern='^[ 0-9]+$' maxlength=9 name=bik value = '".$row['BIK']."'> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>Корпоративный счет (юр.лица):</label></td>
										<td> 
										<?php 
											if($res==-1){
												echo "<input type=text name=corAc value = > ";	
											}
											else{
												echo "<input type=text name=corAc value = '".$row['CorrespondentAccount']."'> ";
											}
											?>
										</td>								
									</tr>
									<tr> <td><label>Согласие на обработку персональных данных (физ.лица):</label></td>
										<td>
											<?php 
											echo "<input type=checkbox " . ( ( $row["ConcentOnPersonalData"] == 1 ) ? "checked " : "" ) . " name=consent >";
											?>
										</select>
										
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
            								echo "<a href='/ManagementCompany/?table=Abonents'>Отменить</a>";
            							?>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
        										echo "<a href='../index.php?table=PersonalAccounts&AbonentId=" . $res . "' target='_blank'>Лицевые счета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
    										echo "</td>";
										echo "</tr>";
									}
								?>
							    </table>
							</div>
							<input type="hidden" name="elem_table" value="Abonents">
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

