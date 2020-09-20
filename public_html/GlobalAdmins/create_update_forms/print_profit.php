<?php
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_header.php';
	require $_SERVER['DOCUMENT_ROOT'].'/GlobalAdmins/_admin_header.php';
?>

<body>
    <div class="main">
        <div class="container">
            <div class="print_logo">
                <span><b>ПЛАТЕЖНЫЙ ДОКУМЕНТ</br>
                для внесения платы за содержание и ремонт жилого помещения и предоставление коммунальных услуг </b></span>
            </div>
        	<?php
        	    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        	    
        	    $AccountId = $_GET[ 'AccountId' ];
        	    $Period    = $_GET[ 'Period' ];	
        		$query     = "SELECT 
        							IFNULL( Abonents.Name, '' ) AS AbonentName,
        							IFNULL( Objects.Name, '' ) AS ObjectName, 
        							IFNULL( Objects.KadastrNo, '' ) AS ObjectKadastrNo,
        							IFNULL( PersonalAccounts.Name, '' ) AS AccountName,
        							IFNULL( Services.Name, '' ) AS ServiceName,
        							IFNULL( Units.Name, '' ) AS UnitName,
        							Profit.Volume AS Volume,	
        							Profit.Portion AS Portion,
        							Profit.Сoefficient AS Сoefficient,
        							Profit.Price AS Price,
        							Profit.Summa AS Summa,
        							IFNULL( Total.Summa, 0 ) AS Total,
        							IFNULL( Debt.Summa, 0 ) AS Debt,
        							IFNULL( Payment.Summa, 0 ) AS Payment
        						FROM (	
        							SELECT 
        								Profit.AccountId AS AccountId,
        								Profit.ServiceId AS ServiceId,
        								SUM( CASE
        										 WHEN ( Profit.CalculationType = 1 ) THEN Profit.NormativeCount
        										 WHEN ( Profit.CalculationType = 2 ) THEN Profit.Indications
        										 WHEN ( Profit.CalculationType = 3 ) THEN Profit.FixedSumma
        										 WHEN ( Profit.CalculationType = 4 ) THEN Profit.SharedIndications
        										 WHEN ( Profit.CalculationType = 5 ) THEN Profit.SharedIndications
        									 END ) AS Volume,	
        								Profit.Portion AS Portion,
        								CASE
        									WHEN ( Profit.CalculationType = 1 ) THEN Profit.Сoefficient
        									WHEN ( Profit.CalculationType = 2 ) THEN Profit.Сoefficient
        									WHEN ( Profit.CalculationType = 3 ) THEN Profit.Сoefficient
        									WHEN ( Profit.CalculationType = 4 ) THEN PortionSquare * ( 1 + Profit.Сoefficient )
        									WHEN ( Profit.CalculationType = 5 ) THEN PortionIndications * ( 1 + Profit.Сoefficient )
        								END AS Сoefficient,
        								CASE
        									WHEN ( Profit.CalculationType = 3 ) THEN 0
        									ELSE Profit.Price
        								END AS Price,
        								SUM( Profit.Summa ) AS Summa
        							FROM
        								Profit AS Profit
        							LEFT JOIN Services AS Services ON
        								( Services.ServiceId = Profit.ServiceId )
        							WHERE
        								( Profit.AccountId = " . $AccountId . " ) AND
        								( Profit.Period = ( LAST_DAY( '" . $Period . "' ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND ) )
        							GROUP BY
        								Profit.AccountId,
        								Profit.ServiceId,
        								Profit.Portion,
        								CASE
        									WHEN ( Profit.CalculationType = 1 ) THEN Profit.Сoefficient
        									WHEN ( Profit.CalculationType = 2 ) THEN Profit.Сoefficient
        									WHEN ( Profit.CalculationType = 3 ) THEN Profit.Сoefficient
        									WHEN ( Profit.CalculationType = 4 ) THEN PortionSquare * ( 1 + Profit.Сoefficient )
        									WHEN ( Profit.CalculationType = 5 ) THEN PortionIndications * ( 1 + Profit.Сoefficient )
        								END,
        								CASE
        									WHEN ( Profit.CalculationType = 3 ) THEN 0
        									ELSE Profit.Price
        								END
        							ORDER BY
        								IFNULL( Services.Name, '' ),
        								Profit.CalculationType ) AS Profit
        						LEFT JOIN PersonalAccounts AS PersonalAccounts ON
        							( PersonalAccounts.AccountId = Profit.AccountId )
        						LEFT JOIN Abonents AS Abonents ON
        							( Abonents.AbonentId = PersonalAccounts.AbonentId )
        						LEFT JOIN Objects AS Objects ON
        							( Objects.ObjectId = PersonalAccounts.ObjectId )
        						LEFT JOIN Services AS Services ON
        							( Services.ServiceId = Profit.ServiceId )
        						LEFT JOIN Units AS Units ON
        							( Units.UnitId = Services.UnitId )
        						LEFT JOIN (	SELECT 
        										Profit.AccountId AS AccountId,
        										SUM( Profit.Summa ) AS Summa
        									FROM
        										Profit AS Profit
        									WHERE
        										( Profit.AccountId = " . $AccountId . " ) AND
        										( Profit.Period = ( LAST_DAY( '" . $Period . "' ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND ) )
        									GROUP BY
        										Profit.AccountId ) AS Total ON
        							( Total.AccountId = Profit.AccountId )
        						LEFT JOIN (	SELECT 
        										Debt.AccountId AS AccountId,
        										SUM( Debt.Summa ) AS Summa
        									FROM
        										Debt AS Debt
        									WHERE
        										( Debt.AccountId = " . $AccountId . " ) AND
        										( Debt.Period = DATE_ADD( LAST_DAY( '" . $Period . "' ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND, INTERVAL -1 MONTH ) )
        									GROUP BY
        										Debt.AccountId ) AS Debt ON
        							( Debt.AccountId = Profit.AccountId )
        						LEFT JOIN (	SELECT 
        										Payment.AccountId AS AccountId,
        										SUM( Payment.Summa ) AS Summa
        									FROM
        										Payment AS Payment
        									WHERE
        										( Payment.AccountId = " . $AccountId . " ) AND
        										( Payment.Date >= ( LAST_DAY( '" . $Period . "' ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND ) ) AND 
        										( Payment.Date < DATE_ADD( LAST_DAY( '" . $Period . "' ) + INTERVAL 1 DAY - INTERVAL 1 MONTH + INTERVAL 0 SECOND, INTERVAL 1 MONTH ) )
        									GROUP BY
        										Payment.AccountId ) AS Payment ON
        							( Payment.AccountId = Profit.AccountId )";
        										
        		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
        		$months = array( '1'=>'Январь', '2'=>'Февраль', '3'=>'Март', '4'=>'Апрель', '5'=>'Май', '6'=>'Июнь', '7'=>'Июль', '8'=>'Август', '9'=>'Сентябрь', '10'=>'Октябрь', '11'=>'Ноябрь', '12'=>'Декабрь' );
        	?>
            <div class='print_wrap'>
                
                    <?php
        				$index = 0;
        				$total = 0;
        			
        				if ( $row = mysqli_fetch_array( $result ) ) {
        				    echo "<div class='print_wrap_left'>";
        				        echo "<span>Раздел 1. <b>Сведения о плательщике</b></span>";
            				    echo "<div class='print_block_left'>";
                					echo "Период: <b>" . $months[ date( 'n', strtotime( $Period ) ) ] . " " . date( 'Y', strtotime( $Period ) ) . " г.</b><br>";
                					echo "Абонент: <b>" . $row[ "AbonentName" ]."</b><br>";
                					echo "Адрес: <b>" . $row[ "ObjectName" ]."</b><br>";
                					echo "Лицевой счёт: <b>" . $row[ "AccountName" ]."</b><br>";
            					echo "</div>";
        					echo "</div>";
        					echo "<div class='print_wrap_right'>";
        					    echo "<span>Раздел 2. <b>Информация по задолженности</b></span>";
            					echo "<div class='print_block_left'>";
                					echo "Задолженность на начало периода: <b>" . number_format( $row[ 'Debt' ], 2, ',', $thousands_sep = '&nbsp;' ) . "</b><br>";
                					echo "Оплачено в текущем месяце: <b>" . number_format( $row[ 'Payment' ], 2, ',', $thousands_sep = '&nbsp;' ) . "</b><br>";
            					echo "</div>";
        					echo "</div>";
        					$total = $row[ "Total" ];
        				}
                    ?>
                
            </div>
            <div class="print_table">
                <span>Раздел 3. <b>Расчёт размера платы за содержание и ремонт жилого помещения и коммунальные услуги</b></span>
                <table width="100%">
                    <tbody>
                        <tr>
                            <th>№ п/п</th>
                            <th>Вид услуги</th>
                            <th>Ед. изм.</th>
                            <th>Объем</th>
                            <th>Доля</th>
                            <th>Коэффициент</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                        </tr>
                        <?php
                        	do {
        						$index = $index + 1;
        						
                        	    echo '<tr>';
                            	    echo '<td align="right">' . $index . '&nbsp;</td>';
                            	    echo '<td>' . $row[ 'ServiceName' ] . '</td>';
                            	    echo '<td align="center">' . $row[ 'UnitName' ] . '</td>';
                            	    echo '<td align="right">' . number_format( $row[ 'Volume' ], 3, ',', $thousands_sep = '&nbsp;' ) . '</td>';
                            	    echo '<td align="right">' . number_format( $row[ 'Portion' ], 2, ',', $thousands_sep = '&nbsp;' ) . '</td>';
                            	    echo '<td align="right">' . number_format( $row[ 'Сoefficient' ], 3, ',', $thousands_sep = '&nbsp;' ) . '</td>';
                            	    echo '<td align="right">' . number_format( $row[ 'Price' ], 2, ',', $thousands_sep = '&nbsp;' ) . '</td>';
                            	    echo '<td align="right">' . number_format( $row[ 'Summa' ], 2, ',', $thousands_sep = '&nbsp;' ) . '</td>';
                        	    echo '<tr>';
                        	} while( $row = mysqli_fetch_array( $result ) );
        						
        					echo '<tr>';
        						echo '<td colspan="7"><b>ИТОГО<b/></td>';
        						echo '<td align="right"><b>' . number_format( $total, 2, ',', $thousands_sep = '&nbsp;' ) . '<b/></td>';
        					echo '<tr>';
                    	?>
                    </tbody>
                </table>
        	</div>
        	</br>
        	<a href = '/GlobalAdmins/?table=Profit'>Отменить</a>
        </div>
	</div>
</body>
</html>