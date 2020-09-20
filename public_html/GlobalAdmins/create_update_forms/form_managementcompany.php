<?php
    require $_SERVER[ 'DOCUMENT_ROOT'] . '/includes/table_names.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
?>
<body>
	<?php
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$res = $_GET["CompanyId"];
		$fn  = $names_ar[ 'ManagementCompany' ];
		
	?>
	
	<div class="main">
		<div class="container content">
			<div class="main__left">
				
					<div class="container">
						<div class="content__title">
							<h5>Управляющая компания</h5>
						</div>
						<form class="" method="post" action="/GlobalAdmins/create_update_query/update_query_managmentcompany.php">
							<input type="hidden" name="elem_id" value='<?php echo $res?>'>
							<div class="form_wrapper">
								<table>
									<?php
										$query2 ="SELECT * FROM Regions ORDER BY Regions.RegionСode";
										$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link)); 
                                        
										if ($res != -1){
											//$query1 ="SELECT * FROM ManagementCompany WHERE CompanyId = ".$res;
											$query1 ="SELECT  ManagementCompany.Name,ManagementCompany.FullName, ManagementCompany.LegalAddress, ManagementCompany.ActualAddress, ManagementCompany.EMail, ManagementCompany.Phone, ManagementCompany.INN, ManagementCompany.KPP, ManagementCompany.OGRN, ManagementCompany.PositionHead, ManagementCompany.FIO, ManagementCompany.PaymentAccount, ManagementCompany.Bank, ManagementCompany.BIK, ManagementCompany.CorrespondentAccount, ManagementCompany.CompanyId, ManagementCompany.RegionId
                      							FROM ManagementCompany
                      							WHERE  CompanyId = ".$res; 
                      							


											$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 

											$num_columns = $result1->field_count;

											while($row1 = mysqli_fetch_array($result1)){
												echo "<tr>";
                                        			echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
                                        			echo "<td><input type='text' id='elem_0' name='fn[]' value='".$row1['Name']."' required></td>";
                                        		echo "</tr>";
                                                
                                                echo "<tr>";
                                        			echo "<td><label for='elem_1'>".$fn[1].":</label></td>";
                                        			echo "<td><input type='text' id='elem_1' name='fn[]' value='".$row1['FullName']."' required></td>";
                                        		echo "</tr>";
                                                
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_2'>".$fn[2].":</label></td>";
                                        			echo "<td><input type='text' id='elem_2' name='fn[]' value='".$row1['LegalAddress']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_3'>".$fn[3].":</label></td>";
                                        			echo "<td><input type='text' id='elem_3' name='fn[]' value='".$row1['ActualAddress']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_4'>".$fn[4].":</label></td>";
                                        			echo "<td><input type='text' id='elem_4' name='fn[]' value='".$row1['EMail']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_5'>".$fn[5].":</label></td>";
                                        			echo "<td><input type='text' id='elem_5' name='fn[]' value='".$row1['Phone']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_6'>".$fn[6].":</label></td>";
                                        			echo "<td><input type='text' pattern='^[ 0-9]+$' maxlength=12 id='elem_6' name='fn[]' value='".$row1['INN']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_7'>".$fn[7].":</label></td>";
                                        			echo "<td><input type='text' pattern='^[ 0-9]+$' maxlength=9 id='elem_7' name='fn[]' value='".$row1['KPP']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_8'>".$fn[8].":</label></td>";
                                        			echo "<td><input type='text' pattern='^[ 0-9]+$' id='elem_8' name='fn[]' value='".$row1['OGRN']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_9'>".$fn[9].":</label></td>";
                                        			echo "<td><input type='text' id='elem_9' name='fn[]' value='".$row1['PositionHead']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_10'>".$fn[10].":</label></td>";
                                        			echo "<td><input type='text' id='elem_10' name='fn[]' value='".$row1['FIO']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_11'>".$fn[11].":</label></td>";
                                        			echo "<td><input type='text' id='elem_11' name='fn[]' value='".$row1['PaymentAccount']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_12'>".$fn[12].":</label></td>";
                                        			echo "<td><input type='text' id='elem_12' name='fn[]' value='".$row1['Bank']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_13'>".$fn[13].":</label></td>";
                                        			echo "<td><input type='text' pattern='^[ 0-9]+$' maxlength=9 id='elem_13' name='fn[]' value='".$row1['BIK']."' required></td>";
                                        		echo "</tr>";
                                        
                                        		echo "<tr>";
                                        			echo "<td><label for='elem_14'>".$fn[14].":</label></td>";
                                        			echo "<td><input type='text' pattern='^[ 0-9]+$' id='elem_14' name='fn[]' value='".$row1['CorrespondentAccount']."' required></td>";
                                        		echo "</tr>";
												
												echo "<tr>";
													echo "<td><label for='elem_15'>".$fn[15].":</label></td>";
													echo "<td>";
												    echo "<select class='select_db' name='fn[]'>";
												        
													while($row2 = mysqli_fetch_array($result2)){
														
														if($row2[0] == $row1['RegionId']){
															echo "<option selected value=".$row2[0].">".$row2[1].", ".$row2[2]."</option>";
														}
														else echo "<option value=".$row2[0].">".$row2[1].", ".$row2[2]."</option>";
														
													}
													echo "</select>";
													echo "</td>";


												echo "</tr>";

											}
										}
										else{

											echo "<tr>";
                                    			echo "<td><label for='elem_0'>".$fn[0].":</label></td>";
                                    			echo "<td><input type='text' id='elem_0' name='fn[]' required></td>";
                                    		echo "</tr>";
                                            
                                            echo "<tr>";
                                    			echo "<td><label for='elem_1'>".$fn[1].":</label></td>";
                                    			echo "<td><input type='text' id='elem_1' name='fn[]' required></td>";
                                    		echo "</tr>";
                                            
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_2'>".$fn[2].":</label></td>";
                                    			echo "<td><input type='text' id='elem_2' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_3'>".$fn[3].":</label></td>";
                                    			echo "<td><input type='text' id='elem_3' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_4'>".$fn[4].":</label></td>";
                                    			echo "<td><input type='text' id='elem_4' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_5'>".$fn[5].":</label></td>";
                                    			echo "<td><input type='text' id='elem_5' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_6'>".$fn[6].":</label></td>";
                                    			echo "<td><input type='text' pattern='^[ 0-9]+$' maxlength=12 
                                    			id='elem_6' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_7'>".$fn[7].":</label></td>";
                                    			echo "<td><input type='text' pattern='^[ 0-9]+$' maxlength=9
                                    			id='elem_7' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_8'>".$fn[8].":</label></td>";
                                    			echo "<td><input type='text' pattern='^[ 0-9]+$' id='elem_8' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_9'>".$fn[9].":</label></td>";
                                    			echo "<td><input type='text' id='elem_9' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_10'>".$fn[10].":</label></td>";
                                    			echo "<td><input type='text' id='elem_10' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_11'>".$fn[11].":</label></td>";
                                    			echo "<td><input type='text' id='elem_11' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_12'>".$fn[12].":</label></td>";
                                    			echo "<td><input type='text' id='elem_12' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_13'>".$fn[13].":</label></td>";
                                    			echo "<td><input type='text' pattern='^[ 0-9]+$' maxlength=9 id='elem_13' name='fn[]' required></td>";
                                    		echo "</tr>";
                                    
                                    		echo "<tr>";
                                    			echo "<td><label for='elem_14'>".$fn[14].":</label></td>";
                                    			echo "<td><input type='text' pattern='^[ 0-9]+$'  id='elem_14' name='fn[]' required></td>";
                                    		echo "</tr>";
                                        		
											echo "<tr>";
												echo "<td><label for='elem_15'>".$fn[15].":</label></td>";
												echo "<td>";
											    echo "<select class='select_db' name='fn[]'>";
											        
												while($row2 = mysqli_fetch_array($result2)){
													echo "<option value=".$row2[0].">".$row2[1].", ".$row2[2]."</option>";
												}
												echo "</select>";
												echo "</td>";


											echo "</tr>";
										}
									?>
								</table>
							</div>
							<div>
							    <table width='100%'>
								<tr>
									<td>
										<input type="submit" class="confirm" value="Сохранить">
										<a href="/GlobalAdmins/?table=ManagementCompany">Отменить</a>
									</td>
								</tr>
								<?php
									if ( $res != -1 ) {
										echo "<tr><td><br><b>См. также:</b></td></tr>";
										echo "<tr>";
    										echo "<td>";
        										echo "<a href='../index.php?table=Objects&CompanyId=" . $res . "' target='_blank'>Объекты недвижимости</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=Services&CompanyId=" . $res . "' target='_blank'>Коммунальные услуги</a>";
            									echo "&nbsp;&nbsp;&nbsp;";
        										
        									    echo "<a href='../index.php?table=Tariffs&CompanyId=" . $res . "' target='_blank'>Тарифы</a>";
            									echo "&nbsp;&nbsp;&nbsp;";
        									
        										echo "<a href='../index.php?table=Devices&CompanyId=" . $res . "' target='_blank'>Приборы учёта</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
        										
        										echo "<a href='../index.php?table=PersonalAccounts&CompanyId=" . $res . "' target='_blank'>Лицевые счета</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
    										echo "</td>";
										echo "</tr>";
										
										echo "<tr>";
										    echo "<td>";
										        echo "<a href='../index.php?table=Admins&CompanyId=" . $res . "' target='_blank'>Администраторы УК</a>";
        										echo "&nbsp;&nbsp;&nbsp;";
										        
    										    echo "<a href='../index.php?table=AccountsQuery&CompanyId=" . $res . "' target='_blank'>Запросы пользователей на добавление лицевых счетов</a>";
            									echo "&nbsp;&nbsp;&nbsp;";
    										echo "</td>";
										echo "</tr>";
									}
								?>
							    </table>
							</div>
							<input type="hidden" name="elem_table" value="ManagementCompany">
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