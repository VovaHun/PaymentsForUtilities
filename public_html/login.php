<?php 
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/connection.php';

	$data = $_POST;
	
	// Нажатие на кнопку авторизации
	if ( isset($data['do_login']) )
	{
		// Проверка полей формы
		$errors = array();
		if ( trim($data['login']) == '' )
		{
			$errors[] = 'Введите логин';
		}
		
		if ( $data['password'] == '' )
		{
			$errors[] = 'Введите пароль';
		}
		
		$login = $data['login'];
		$query = mysqli_query($link, 'SELECT * FROM `Users` WHERE `Login` = "'.$login.'"') or die("Ошибка " . mysqli_error($link));
		$user = mysqli_fetch_assoc($query);
		
		if ($user)
		{
			// Логин существует
			if ( password_verify($data['password'], $user['Password']))
			{
				// Авторизация пользователя
				$_SESSION['logged_user'] = $user;
				header("Location: index.php");
			}
			// Пароль не верен
			else
			{
				$errors[] = 'Неверно введен пароль!';
			}
		}
		// Логин не существует
		else
		{
			$errors[] = 'Пользователь с таким логином не найден!';
		}
	}
?>

<head>
	<link rel='stylesheet' href='/styles/style_login.css'>
</head>

<body>
	
	<div class = "InputForm">
		
		<div class = "ErrorsShift"> <?php echo @array_shift($errors); ?> </div>
		
		<form action = "login.php" method = "POST">
			
			<div class = "InputDiv">
				<strong class = "InputStrong">Логин</strong><br/>
				<input class = "InputField" type = "text" name = "login" value = "<?php echo @$data['login']; ?>">
			</div>

			<div class = "InputDiv">
				<strong class = "InputStrong">Пароль</strong><br/>
				<input class = "InputField" type = "password" name = "password">
			</div>

			<button class = "InputButton" type = "submit" name = "do_login">Войти</button>
			
			<p><a href = "signupEmail.php" class = "InputHref"> Нет аккаунта? Создайте его! </a>
			
		</form>
		
	</div>
	
</body>