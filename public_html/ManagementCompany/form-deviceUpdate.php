<?php
	include_once 'Database.php';

	$Id             = $_POST['Id'];
	$Number         = $_POST['Number'];
	$Release_date   = $_POST['Release_date'];
	$Start_readings = $_POST['Start_readings'];
	$Model          = $_POST['Model'];
    $Manufacturer   = $_POST['Manufacturer'];
    $Next_check_date= $_POST['Next_check_date'];
	
	if ( $Id == -1 ) {
		$sql = 'INSERT INTO devices
				(number, release_date, start_readings, model, manufacturer, next_check_date)
				VALUES (\'' . $Number . '\',
						\'' . $Release_date . '\',
						\'' . $Start_readings . '\',
						\'' . $Model . '\'
                        \'' . $Manufacturer . '\',
                        \'' . $Next_check_date . '\',)';	
		mysql_query( $sql ) or die( 'Ошибка добавления записи. ' . mysql_error() );
	}
	else {
		$sql = 'UPDATE devices 
				SET number = \'' . $Number . '\',
					Release_date = \'' . $Release_date . '\',
					Start_readings = \'' . $Start_readings . '\',
					Model = \'' . $Model . '\',
                    Manufacturer = \'' . $Manufacturer . '\',
                    Next_check_date = \'' . $Next_check_date . '\'
				WHERE (Id = ' . $Id . ')';	
		mysql_query( $sql ) or die( 'Ошибка изменения записи. ' . mysql_error() );
	}

	mysql_close();
	
	header( 'Refresh: 0; url=form-devices.php' );
?>
