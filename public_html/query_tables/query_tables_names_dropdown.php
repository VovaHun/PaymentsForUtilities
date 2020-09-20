	<?php	
		//Список таблиц в бд, вводится вручную
		$table_names = array("Абоненты","Приборы учетов по лицевым счетам","Виды нормативов (тарифов) по лицевым счетам","Состав коммунальных услуг" ,"Лицевые счета пользователей", "Запросы на добавление лицевых счетов", "Администраторы управляющих компаний","Боты","Общедомовые приборы учета","Поставщики коммунальных услуг","События приборов учета","Показания приборов учета","Модели приборов учета","Приборы учета","Глобальные администраторы","Управляющие компании","Использование моделей приборов учета","Объекты недвижимости","Лицевые счета","Регионы","Коммунальные услуги","Виды тарифов","Тарифы","Единицы измерения","Пользователи","Пользователи (запрос)");
		// вывод данных таблицы
		$query4 ="SHOW TABLES FROM " . $database;
		$result4 = mysqli_query($link, $query4) or die("Ошибка " . mysqli_error($link)); 

		//Вывод названий таблиц
		if($result4)
		{
			echo "<div class='dropdown'>";
			echo "<button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Таблицы</button>";
			echo "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";

			$m = 0;

		    while($row4 = mysqli_fetch_array($result4)){
		    	$text = "/GlobalAdmins/?table=".$row4[0]."";

		    	echo "<a class='dropdown-item' href='".$text."'>".$table_names[$m]."</a>";


		    	$m++;
			}
			
			echo "</div>";
			echo "</div>";
		}
	?>




