<?php
	require $_SERVER['DOCUMENT_ROOT'].'/db_connect/connection.php';
	
	// Проверка хэша
	if ($_GET['hash'])
	{
		$id = $_GET['id'];
		$hash = $_GET['hash'];
		
		$loginCheckQuery = mysqli_query($link, 'SELECT * FROM `UsersQuery` WHERE `QueryId` = "'.$id.'"') or die("Ошибка " . mysqli_error($link));
		$userQuery = mysqli_fetch_assoc($loginCheckQuery);
		
		if (@count($userQuery) > 0)
		{
			if (md5($userQuery['Email']) == $hash)
			{
				// Проверка на существование пользователя
				$loginCheckQuery = mysqli_query($link, 'SELECT * FROM `Users` WHERE `Login` = "'.$userQuery['Login'].'"') or die("Ошибка " . mysqli_error($link));
				if ( @count(mysqli_fetch_assoc($loginCheckQuery)) > 0)
				{
					echo "Пользователь с таким логином уже существует!";
				}
				else
				{
					// Регистрация
					$login = $userQuery['Login'];
					$password = $userQuery['Password'];
					$name = $userQuery['Name'];
					//$gender = $userQuery['Gender'];
					$gender = ( is_null( $userQuery[ 'Gender' ] ) ? 0 : $userQuery[ 'Gender' ] );
					$email = $userQuery['Email'];
					$emailConsent = $userQuery['EmailNotifications'];
					$phone = $userQuery['Phone'];
					$phoneConsent = $userQuery['PhoneNotifications'];
					$consentOnPersonalData = $userQuery['ConsentOnPersonalData'];
					$socialConsent = $userQuery['SocialNotifications'];
					$date = date("Y-m-d H:i:s");
					
					// Переносим данные пользователя в постоянную таблицу Users
					$query = "INSERT INTO Users ( Login, Password, Name, Gender, Email, EmailNotifications, Phone, PhoneNotifications, AppealType, Appeal, Comment, ConsentOnPersonalData, BotId, SocialId, SocialNotifications, RegistrationDate ) VALUES ( '" . $login. "', '". $password ."', '" . $name . "', '" . $gender . "', '" . $email . "', " . $emailConsent . ", '" . $phone . "', " . $phoneConsent . ", NULL, NULL, NULL, " . $consentOnPersonalData . ", NULL, NULL, " . $socialConsent . ", '" . $date . "' )";
					$result = mysqli_query( $link, $query ) or die( "Ошибка: " . mysqli_error( $link ) );
					
					// Удаляем запись-запрос пользователя из таблицы запросов
					$clearingQuery = mysqli_query($link, 'DELETE FROM UsersQuery WHERE `QueryId` = '.$userQuery['QueryId'].'') or die("Ошибка " . mysqli_error($link));
					
					// Создание сессии
					$userIdRequest = mysqli_query($link, 'SELECT * FROM `Users` WHERE `Login` = "'.$login.'"') or die("Ошибка " . mysqli_error($link));
					$user = mysqli_fetch_assoc($userIdRequest);
					$_SESSION['logged_user'] = $user;
					header("Location: index.php");
				}
			}
			else
			{
				echo "Что-то пошло не так со сравнением хэша";
			}
		}
		else 
		{
			echo "Что-то пошло не так с поиском записи";
		}
	}
	else 
	{
		echo "Что-то пошло не так с получением хэша";
	}
?>