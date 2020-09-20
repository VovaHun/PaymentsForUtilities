<?php
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/config.php';
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/smsc_api.php';

	$data = $_POST;

	// Нажатие на кнопу регистрации
	if ( isset($data['do_signup']) )
	{
		// Здесь будут ещё проверки 		
		
		// Проверка полей формы
		$errors = array();
		if ( $data['password_confirm'] != $data['password'] )
		{
			$errors[] = 'Повторный пароль введен не верно!';
		}
		
		if ( md5($data['verify_code']) != $_SESSION['hash'] )
		{
			$errors[] = 'Неверный код подтверждения!';
		}

		// Проверка на существование одинакового логина
		$login = $data['login'];
		$loginCheckQuery = mysqli_query($link, 'SELECT * FROM `Users` WHERE `Login` = "'.$login.'"') or die("Ошибка " . mysqli_error($link));
		if ( @count(mysqli_fetch_assoc($loginCheckQuery)) > 0)
		{
			$errors[] = 'Пользователь с таким логином уже существует!';
		}
    
		if ( empty($errors) )
		{
			// Регистрация
			$login = $data['login'];
			$password = password_hash($data['password'], PASSWORD_DEFAULT);
			$name = $data['name'];
			$gender = $data['gender'];
			$email = $data['email'];
			$emailConsent    = ( ( isset( $_POST[ 'emailConsent' ] ) == true ) ? 1 : 0 );
			$phone = $data['phone'];
			$phoneConsent    = ( ( isset( $_POST[ 'phoneConsent' ] ) == true ) ? 1 : 0 );
			$consentOnPersonalData = ( ( isset( $_POST[ 'consentOnPersonalData' ] ) == true ) ? 1 : 0 );
			$socialConsent = 0;
			$date = date("Y-m-d H:i:s");
			
			$query = "INSERT INTO Users ( Login, Password, Name, Gender, Email, EmailNotifications, Phone, PhoneNotifications, AppealType, Appeal, Comment, ConsentOnPersonalData, BotId, SocialId, SocialNotifications, RegistrationDate ) VALUES ( '" . $login. "', '". $password ."', '" . $name . "', " . $gender . ", '" . $email . "', " . $emailConsent . ", '" . $phone . "', " . $phoneConsent . ", NULL, NULL, NULL, " . $consentOnPersonalData . ", NULL, NULL, " . $socialConsent . ", '" . $date . "' )";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );
			
			// Создание сессии
			$userIdRequest = mysqli_query($link, 'SELECT * FROM `Users` WHERE `Login` = "'.$login.'"') or die("Ошибка " . mysqli_error($link));
			$user = mysqli_fetch_assoc($userIdRequest);
			$_SESSION['logged_user'] = $user;
			header("Location: index.php");
		}
	}
	
	// Нажатие на кнопку "Выслать код повторно"
	if ( isset($data['send_code']) )
	{
		$code_message = array();
			
		// Генерация кода подтверждения
		$verifyCode = ok_code($data['phone']);
		
		// Хэш кода подтверждения
		$hash = md5($verifyCode);
		
		// Отправка СМС
		$sms = send_sms($data['phone'], $verifyCode);
		
		// Проверка отправки СМС
		if ($sms[1] > 0)
		{
			// Запись хэша кода подтверждения в сессию
			$_SESSION['hash'] = $hash;
			$code_message[] = 'Код подтверждения отправлен на номер '.$data['phone'];
		}
		else
		{
			$errors[] = "Что-то пошло не так с отправкой кода";
		}
	}
	
	// Функция генерации кода
	function ok_code($s)
	{
		return hexdec(substr(md5($s."<секретная строка>"), 7, 5));
	}
?>

<head>
	<link rel='stylesheet' href='/styles/style_login.css'>
</head>

<body>
	
	<div class = "InputForm">
		
		<div class = "RegistrationOptions">
			
			<div class = "RegOptionLeft">
				<a href = "signupEmail.php" class = "RegOptionNotChosen">Электронная почта</a>
			</div>
			
			<div class = "RegOptionRight">
				<a href = "signupPhone.php" class = "RegOptionChosen">Мобильный телефон</a>
			</div>
			
		</div>
		
		<div class = "ErrorsShift"> <?php echo @array_shift($errors); ?> </div>
		
		<form action = "/signupEmail.php" method = "POST">
		
			<div class = "InputDiv">
				<strong class = "InputStrong">Ваш логин</strong>
				<input class = "InputField" type = "text" name = "login" value = "<?php echo @$data['login']; ?>" required>
			</div>
			
			<div class = "InputDiv">
				<strong class = "InputStrong">Ваше имя</strong>
				<input class = "InputField" type = "text" name = "name" value = "<?php echo @$data['name']; ?>" required>
			</div>
			
			<div class = "InputDiv">
				<strong class = "InputStrong">Ваш пол</strong>
				<select name = "gender" style = 'height: 25px'>
					<option selected value = "NULL"></option>
					<option value = "1">Мужской</option>
					<option value = "2">Женский</option>
				</select>
			</div>
			
			<div class = "InputDiv">
				<strong class = "InputStrong">Адрес электронной почты</strong>
				<input class = "InputField" type = "email" name = "email" value = "<?php echo @$data['email']; ?>">
			</div>
			
			<div class = "InputDiv">
				<strong class = "InputStrong">Номер телефона</strong>
				<input class = "InputField" type = "text" name = "phone" value = "<?php echo @$data['phone']; ?>" required>
			</div>
			
			<div class = "InputDiv">
				<strong class = "InputStrong">Ваш пароль</strong>
				<input class = "InputField" type = "password" name = "password" required>
			</div>

			<div class = "InputDiv">
				<strong class = "InputStrong">Повторите пароль</strong>
				<input class = "InputField" type = "password" name = "password_confirm" required>
			</div>
			
			<div class = "InputDiv">
				<strong class = "InputStrong">Код подтверждения</strong>
				<input class = "InputField" type = "text" name = "verify_code" value = "<?php echo @$data['verify_code']; ?>">
			</div>
			
			<button class = "InputButton" type = "submit" name = "send_code">Выслать код</button>
			
			<div class = "ErrorsShift" style = "color: green"> <?php echo @array_shift($code_message); ?> </div>
			
			<div class = "InputDiv">
				<input type = "checkbox" name = "emailConsent"/>
				<strong class = "InputStrong">Согласие на получение уведомлений по электронной почте</strong>
			</div>
			
			<div class = "InputDiv">
				<input type = "checkbox" name = "phoneConsent"/>
				<strong class = "InputStrong">Согласие на получение уведомлений по телефону</strong>
			</div>
			
			<div class = "InputDiv">
				<input type = "checkbox" name = "consentOnPersonalData" required>
				<strong class = "InputStrong">Согласие на обработку персональных данных</strong>
			</div>

			<button class = "InputButton" type = "submit" name = "do_signup">Регистрация</button>
			
			<p><a href = "login.php" class = "InputHref"> Аккаунт уже есть? Авторизуйтесь! </a>
			
		</form>
	</div>
</body>