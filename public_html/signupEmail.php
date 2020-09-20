<?php
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/connection.php';
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/config.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';

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
			$queryDate = date("Y-m-d H:i:s");
			
			$query = "INSERT INTO UsersQuery ( Login, Password, Name, Gender, Email, EmailNotifications, Phone, PhoneNotifications, AppealType, Appeal, Comment, ConsentOnPersonalData, BotId, SocialId, SocialNotifications, QueryDate ) VALUES ( '" . $login. "', '". $password ."', '" . $name . "', " . $gender . ", '" . $email . "', " . $emailConsent . ", '" . $phone . "', " . $phoneConsent . ", NULL, NULL, NULL, " . $consentOnPersonalData . ", NULL, NULL, " . $socialConsent . ", '" . $queryDate . "' )";
			$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );
			
			//Отправка письма
			
			$userIdRequest = mysqli_query($link, 'SELECT * FROM `UsersQuery` WHERE `QueryDate` = "'.$queryDate.'"') or die("Ошибка " . mysqli_error($link));
			$user = mysqli_fetch_assoc($userIdRequest);
			
			// Хэш для идентификации пользователя
			$hash = md5($user['Email']);
			
			// Электронная почта получателя
			$sentTo = $user['Email'];
			$userName = $user['Name'];
			
			// Тема письма
			$subject = "Подтверждение регистрации на сайте $websiteName";
			
			// Содержимое письма
			$message = '
                <html>
					<head>
						<title>Подтверждение регистрации на сайте https://'. $websiteWebName .'.com</title>
					</head>
					<body>
						<p>Здравствуйте, ' .$sentTo. '!</p>
						<p>Для завершения регистрации перейдите по данной <a href="https://'. $websiteWebName .'/activate.php?id=' . $user['QueryId'] . '&hash=' . $hash . '">ссылке</a>.</p>
					</body>
                </html>
                ';
			
			$mail = new PHPMailer();
			
			$mail->isSMTP();                   						// Отправка через SMTP
            $mail->Host   = 'smtp.yandex.ru';  						// Адрес SMTP сервера
            $mail->SMTPAuth   = true;          						// Enable SMTP authentication
            $mail->Username   = $smtpUserName;			       		// Яндекс-логин (без домена и @)
            $mail->Password   = $smtpUserPassword;    				// Пароль для приложений
            $mail->SMTPSecure = 'ssl';         						// Шифрование ssl
            $mail->Port   = 465;               						// Порт подключения
			
			$mail->CharSet = 'UTF-8';								// Кодировка
			$mail->setFrom("$websiteMail", "$websiteName");			// Адрес отправителя (email и имя)
			$mail->addAddress("$sentTo", "$userName");				// Адрес получателя (email и имя)
			$mail->Subject = "$subject";							// Тема письма
			$mail->msgHTML("$message");								// Содержимое письма
			
			// Отправка письма
			if ($mail->send())
			{
				// Письмо отправилось
				header("Location: mailWaitingRoom.php");
			}
			else
			{
				// Письмо не отправилось
				$errors[] = 'При отправке письма что-то пошло не так!';
			}
		}
	}
?>

<head>
	<link rel='stylesheet' href='/styles/style_login.css'>
</head>

<body>
	
	<div class = "InputForm">
		
		<div class = "RegistrationOptions">
			
			<div class = "RegOptionLeft">
				<a href = "signupEmail.php" class = "RegOptionChosen">Электронная почта</a>
			</div>
			
			<div class = "RegOptionRight">
				<a href = "signupPhone.php" class = "RegOptionNotChosen">Мобильный телефон</a>
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
				<input class = "InputField" type = "email" name = "email" value = "<?php echo @$data['email']; ?>" required>
			</div>
			
			<div class = "InputDiv">
				<strong class = "InputStrong">Номер телефона</strong>
				<input class = "InputField" type = "text" name = "phone" value = "<?php echo @$data['phone']; ?>">
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