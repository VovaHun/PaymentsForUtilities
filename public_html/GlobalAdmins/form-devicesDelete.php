<?php
	include_once 'Database.php';

	$Id = $_GET['Id'];
	
	$sql = 'DELETE 
		    FROM devices
			WHERE (id = ' . $Id . ')';
	mysql_query( $sql ) or die( 'Ошибка удаления записи. ' . mysql_error() );
	
	// $sql = 'UPDATE Orders  
	// 		SET DoctorId = -1
	// 		WHERE (DoctorId= ' . $Id . ')';	
	// mysql_query( $sql ) or die( 'Ошибка изменения записи. ' . mysql_error() );

	mysql_close();
	
	header( 'Refresh: 0; url=form-devices.php' );
?>