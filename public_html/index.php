<?php
    require $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
?>

<body>
	<?php
		//print_r($user);
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		require $_SERVER['DOCUMENT_ROOT'].'/includes/table_names.php';
		require $_SERVER['DOCUMENT_ROOT'].'/includes/user_header.php';

		if (!isset($_GET["table"])) {
			$active_table = "IncludeTable";
		}
		else $active_table = $_GET["table"];
	?>
	
	<div class="header_bottom">
		<div class="container head_bot" style="background-color: inherit;">
			<h2>Пользовательская панель</h2>
		</div>
	</div>
	
	<div class="main">
		<div class="wrapper__main container">
			<div class="left_area">
				<div class="left_top">
					<div class="title_wrapper">
						<div class="content_title">
							<h6>Список лицевых счетов</h6>
						</div>
					</div>
					
				<?php
					
					 
					$query1 = "SELECT PersonalAccounts.AccountId AS AccountId, PersonalAccounts.Name AS Name
					FROM PersonalAccounts, AccountUsers
					WHERE AccountUsers.UserId = ".$user['UserId']."
					AND AccountUsers.AccountId = PersonalAccounts.AccountId
				    ORDER BY PersonalAccounts.AccountId";

					$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));

					$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link));
					if($result1)
					{
						echo "<ul>";
						while ($row1 = mysqli_fetch_array($result1)) 
						{
						    echo "<li>";    
							//echo "<a href='?table=".$active_table."&persid=".$row1[0]." '>Счёт №  ".$row1[1]."  </a>";
							echo "<a href='?table=IncludeTable&persid=".$row1[0]." '>Счёт №  ".$row1[1]."  </a>";
							echo "</li>";
					   	}
					   		echo "</ul>";
					}
								
					@$PersonalId = $_GET['persid'];
					
					$query2 = "SELECT AccountsQuery.QueryId, AccountsQuery.AccountName 
								FROM AccountsQuery 
								WHERE AccountsQuery.UserId = ".$user['UserId']." 
								AND NOT AccountsQuery.QueryStatus = 1
								ORDER BY AccountsQuery.QueryStatus";
					$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link));
					$check_reslut = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link));
					
					$query_check = 0;
					if ( @count(mysqli_fetch_assoc($check_reslut)) > 0)
					{
						$query_check = 1;
					}
					
					//if ( @count($result2) > 0 )
					if ( $query_check > 0 )
					{
						echo "<div class = 'title_wrapper'>";
						echo "<div class = 'content_title'>";
						echo "<h6>Запросы</h6>";
						echo "</div>";
						echo "</div>";
					}
					
					if($result2)
					{
						echo "<ul>";
						while ($row2 = mysqli_fetch_array($result2)) 
						{
						    echo "<li>";
							//echo "<a href='?section=Query&personalId=".$row2[0]."'>Счёт № ".$row2[1]."  </a>";
							echo "<a href='?section=Query&personalId=".$row2[0]."'>".$row2[1]."  </a>";
							echo "</li>";
					   	}
					   	echo "</ul>";
					}
					
					echo "<a class='acc_but' href='/create_elem_personalaccount.php'><div class='left_top_but'>Добавить счёт</div></a>";
				?>

				</div>
				
				<?php 
				   if(isset($PersonalId)) 
					{
				?>
				
					<div class="left_bottom">
						<div class="title_wrapper">
							<div class="content_title">
								<h6>Ссылки</h6>
							</div>
						</div>
						<div>
							<ul>
								<li>
									<?php
										$AccountId = $_GET['persid'];
			    					  
			    					  	echo "<a  href='?table=IncludeTable&persid=".$AccountId." '>Ввод показаний</a>"; 
			    					  	
									?>
								</li>
								<li>
									<?php
									
										
										echo "<a href='?table=History&persid=".$AccountId." '>История</a>";
									
									?>
								</li>
								<li><a href='/index.php'>Главная</a></li>
								
							</ul>
						</div>
					</div>
					
				<?php 
					} 
				?>
				
				<?php 
                	if(isset($PersonalId)) 
                	{
                		$date = date("Y-m-d");
                		
                		$date = time();
                		$monthBackOne = strtotime("first day of this month", $date);
                		$monthBackTwo = strtotime("-1 month", $monthBackOne);
                		$monthBackThree = strtotime("-2 month", $monthBackOne);
                		$monthBackFour = strtotime("-3 month", $monthBackOne);
                		$monthBackFive = strtotime("-4 month", $monthBackOne);
                		
                		$AccountId = $_GET['persid'];
                		
                		$months = array( '1'=>'Январь', '2'=>'Февраль', '3'=>'Март', '4'=>'Апрель', '5'=>'Май', '6'=>'Июнь', '7'=>'Июль', '8'=>'Август', '9'=>'Сентябрь', '10'=>'Октябрь', '11'=>'Ноябрь', '12'=>'Декабрь' );
                ?>
                
                	<div class = "left_bottom">
                		<div class = "title_wrapper">
                			<div class = "content_title">
                				<h6>Платежные документы</h6>
                			</div>
                		</div>
                		<div>
                			<ul>
                				<li>
                					<?php
                						echo "<a href = '?table=PrintProfit&persid=". $AccountId ."&date=". date("Y-m-d", $monthBackOne) ." '>". $months[ date( 'n', $monthBackOne ) ] ." ". date( 'Y', $monthBackOne ) ." г." ."</a>";
                					?>
                				</li>
                				<li>
                					<?php
                						echo "<a href = '?table=PrintProfit&persid=". $AccountId ."&date=". date("Y-m-d", $monthBackTwo) ." '>". $months[ date( 'n', $monthBackTwo ) ] ." ". date( 'Y', $monthBackTwo ) ." г." ."</a>";
                					?>
                				</li>
                				<li>
                					<?php
                						echo "<a href = '?table=PrintProfit&persid=". $AccountId ."&date=". date("Y-m-d", $monthBackThree) ." '>". $months[ date( 'n', $monthBackThree ) ] ." ". date( 'Y', $monthBackThree ) ." г." ."</a>";
                					?>
                				</li>
                				<li>
                					<?php
                						echo "<a href = '?table=PrintProfit&persid=". $AccountId ."&date=". date("Y-m-d", $monthBackFour) ." '>". $months[ date( 'n', $monthBackFour ) ] ." ". date( 'Y', $monthBackFour ) ." г." ."</a>";
                					?>
                				</li>
                				<li>
                					<?php
                						echo "<a href = '?table=PrintProfit&persid=". $AccountId ."&date=". date("Y-m-d", $monthBackFive) ." '>". $months[ date( 'n', $monthBackFive ) ] ." ". date( 'Y', $monthBackFive ) ." г." ."</a>";
                					?>
                				</li>
                			</ul>
                		</div>
                	</div>
                	
                <?php 
                	} 
                ?>
				
			</div>
			<div class="right_area" id="right">

				<?php
			      	
					if($PersonalId == NULL & !isset($_GET['section'])){
    					echo "<p class='alert_text'>Выберите лицевой счет</p>";
    				}
    				else if (isset($active_table) & !isset($_GET['section']) ){
    					switch ($active_table) {
    						case "IncludeTable":
    							require $_SERVER['DOCUMENT_ROOT'].'/include_table.php';
    							break; 
    						case "History":
        						require $_SERVER['DOCUMENT_ROOT'].'/history_table.php';
        						break; 
        					case "PrintProfit":
    							require $_SERVER['DOCUMENT_ROOT'].'/print_table.php';
    							break; 
    						
    					}
    				}
    				else if ($_GET['section']=="Query") 
    				{
    					require $_SERVER['DOCUMENT_ROOT'].'/account_query.php';
    				}
    			
                    
			    ?>
			</div>
		</div>
	</div>
<?php
    require $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php';
?>
</body>
</html>