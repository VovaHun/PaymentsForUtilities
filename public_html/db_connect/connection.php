<?php
	$host = 'localhost'; // адрес сервера 
	$database = 'id13074152_utility_payments'; // имя базы данных
	$user = 'id13074152_admin'; // имя пользователя
	$password = 'd<^0@EwT{]1f%a{F'; // пароль

	// подключаемся к серверу
	$link = mysqli_connect($host, $user, $password, $database) 
	    or die("Ошибка " . mysqli_error($link));
	
	// Запускаем сессию
	if(!isset($_SESSION))
	{
        session_start();
	}
?>