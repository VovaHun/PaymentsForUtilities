<?php
	//print mail("name@my.ru", "header", "text");
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    if (mail("vlad-falcon@mail.ru", "Test mail", "Проверка отправки почты")) 
    {
        echo "ok";
    } 
    else
    {
        echo "error";
    }
?>