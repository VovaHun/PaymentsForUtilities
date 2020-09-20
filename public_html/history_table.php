<div class = "">
	<?php 
		
		if($PersonalId == NULL){
			$PersonalId = 0;
		}
		else{
			$PersonalId = $_GET['persid'];
		}
		//print_r($PersonalId);
		
		if(!isset($PersonalId) || $PersonalId ==-1 )
		{
			echo "<div> Выберите лицевой счет </div>";
		}		
		else 
		{
			$query4 = "SELECT DeviceIndications.*, AccountDevices.AccountId,AccountDevices.TariffId, AccountDevices.DeviceId, TariffTypes.Name, Devices.Name
						FROM DeviceIndications , AccountDevices, TariffTypes, Devices
						WHERE AccountDevices.AccountId=".$PersonalId."
						AND DeviceIndications.DeviceId=AccountDevices.DeviceId 
						AND TariffTypes.TariffId = AccountDevices.TariffId
						AND Devices.DeviceId = AccountDevices.DeviceId";

			@$result3 = mysqli_query($link, $query4) or die("Ошибка " . mysqli_error($link));
		}

		@$page = $_GET['page'];
		$quantity=10;
		$limit=3;

		if(!is_numeric($page)) $page=1;
		if ($page<1) $page=1;

		@$num = mysqli_num_rows($result3);
		$pages = $num/$quantity;
		$pages = ceil($pages);
		$pages++;

		if ($page>$pages) $page = 1;
		
		echo '<strong style="color: #df0000">Страница № ' . $page . '</strong><br /><br />';

		if (!isset($list)) $list=0;
		
		$list=--$page*$quantity;
		
		if($PersonalId == NULL ||$PersonalId==-1 )
		{
			 echo "<div>  </div>";
			 $num_result=0;
		}
		else 
		{ 
			
						
			$limquery = "SELECT DeviceIndications.*, AccountDevices.AccountId, AccountDevices.TariffId, AccountDevices.DeviceId, Services.Name, TariffTypes.Name, DeviceModels.Name, Devices.Name
						FROM AccountDevices
						LEFT JOIN DeviceIndications ON (AccountDevices.DeviceId = DeviceIndications.DeviceId)
						LEFT JOIN Services ON (Services.ServiceId = AccountDevices.ServiceId)
						LEFT JOIN TariffTypes ON (TariffTypes.TariffId = AccountDevices.TariffId)
						LEFT JOIN  Devices ON (Devices.DeviceId = AccountDevices.DeviceId)
						LEFT JOIN  DeviceModels ON (DeviceModels.ModelId = Devices.ModelId)
						WHERE AccountDevices.AccountId = ".$PersonalId." 
						ORDER BY DeviceIndications.Date DESC,
						DeviceIndications.DeviceId DESC
						LIMIT ".$quantity." OFFSET ".$list;

			$limres = mysqli_query($link, $limquery) or die("Ошибка " . mysqli_error($link)); 		
			$num_result = mysqli_num_rows($limres);
		}

		echo "<table id=_data_table>";
		echo  "<tbody>";
		echo  "<tr>";
		echo  "<td id=_st_td_1>Тариф</td>";
		echo  "<td id=_st_td_1>Прибор учета</td>";
		echo  "<td id=_st_td_1>Дата показаний</td>";
		echo  "<td id=_st_td_1>Показания прибора учета</td>";
		echo  "</tr>"; 

		for ($i = 0; $i<$num_result; $i++) 
		{
			$row = mysqli_fetch_array($limres);
		
			echo  "<tr>";
			echo  "<td id=_st_td_2>".$row[8]."</td>";
			echo  "<td id=_st_td_2>".$row[9]." ".$row[10]."</td>";
			$date = strtotime($row[1]);			
			echo "<td id=_st_td_2>".date('d.m.Y',$date)."</td>"; 
			
			echo  "<td id=_st_td_2>".$row[2]."</td>";
			echo  "</tr>";
		}

		echo  "</tbody>";
		echo "</table>";
					
					
		echo "<hr>";

		$_this  = $page+1;
		$start = $_this-$limit;
		$end = $_this+$limit;

		echo 'Страницы: ';
					
        
		// Выводим ссылки "назад" и "на первую страницу"
		if ($page>=1) 
		{
			// Значение page= для первой страницы всегда равно единице, 
			// поэтому так и пишем
			echo '<a href="' . $_SERVER['SCRIPT_NAME'].'?table=History&persid='.$PersonalId.'&page=1"><<</a> &nbsp; ';

			// Так как мы количество страниц до этого уменьшили на единицу, 
			// то для того, чтобы попасть на предыдущую страницу, 
			// нам не нужно ничего вычислять
			echo '<a href="' . $_SERVER['SCRIPT_NAME'].'?table=History&persid='.$PersonalId.'&page=' . $page . '">< </a> &nbsp; ';
		}

		for ($j = 1; $j<$pages; $j++) 
		{
			// Выводим ссылки только в том случае, если их номер больше или равен
			// начальному значению, и меньше или равен конечному значению
			if ($j>=$start && $j<=$end) 
			{
				// Ссылка на текущую страницу выделяется жирным
				if ($j==($page+1)) echo '<a href="'.$_SERVER['SCRIPT_NAME'].'?table=History&persid='.$PersonalId.'
				&page=' . $j . '"><strong style="color: #df0000">' . $j . 
				'</strong></a> &nbsp; ';

				// Ссылки на остальные страницы
				else echo '<a href="'.$_SERVER['SCRIPT_NAME'].'?table=History&persid='.$PersonalId.'&page=' . 
				$j . '">' . $j . '</a> &nbsp; ';
			}
		}

		// Выводим ссылки "вперед" и "на последнюю страницу"
		if ($j>$page && ($page+2)<$j) 
		{
			// Чтобы попасть на следующую страницу нужно увеличить $pages на 2
			echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?table=History&persid='.$PersonalId.'&page=' . ($page+2) . 
			'"> ></a> &nbsp; ';

			// Так как у нас $j = количество страниц + 1, то теперь 
			// уменьшаем его на единицу и получаем ссылку на последнюю страницу
			echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?table=History&persid='.$PersonalId.'&page=' . ($j-1) . 
			'">>></a> &nbsp; ';
		}
	?>
</div>

<style type = "text/css">

 #_st_td_1 {
  text-align: left;
  padding: 5px;
  width: 50%;
  border: 1px solid black;
  padding-left:10px ;
  font: 12pt   georgia ;
}
 #_st_td_2 {
  text-align: left;
  padding: 5px;
  width: 40%;
  border: 1px solid black;
  padding-left:10px ;
  padding-top: 10px;
  padding-bottom: 10px;
  padding-right: 10px;
  font: 12pt sans-serif;
}
 #_st_td_3 {
  text-align: left;
  padding: 5px;
  width: 90%;
  border: 1px solid black;
  padding-left:10px ;
}

tr:nth-child(even) {background-color: #f2f2f2;}

#_div_name{
	font: 15pt sans-serif;
	padding-bottom: 10px;
}

#_data_table{
	width: 95%;
	border: 1px solid black;
}

#btn{	
	background-color: #addfad;
}

</style>