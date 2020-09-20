<?php include_once 'connection.php';         	
	  include_once '_DialogDelete.php';
	
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="admin" />
    <script language="JavaScript" src="_DialogUpdate.js"></script>
<script language="JavaScript" src="_DialogDelete.js"></script>
    <?php
	include("block-links.php")
    ?>
    <title>Форма данных устройств</title>
</head>

<body>
<div id="block-body">
    <div class="container" id="block-header">
        <?php
	       include ("block-header.php");
        ?>
    </div>
    
    <div class="container" id="block-content" style="padding-top: 15px;">
    <dialog id = "dialogUpdate">
        <form id = "formUpdate" action="form-deviceUpdate.php" method="POST">
            <table border="0">
						<tr id = "Id">
							<td>Id</td>
							<td>
								<input name="Id" type="number" min="0" value='' readonly size="100" maxlength="11" style="width:100%">
							</td>
						</tr>
						<tr>
							<td>Номер</td>
							<td>
								<input name="Number" type="text" value='' size="100" maxlength="150" style="width:100%">
							</td>
						</tr>
                        <tr>
							<td>Дата выпуска</td>
							<td>
								<input name="Release_date" type="text" value='' size="100" maxlength="150" style="width:100%">
							</td>
						</tr>
                        <tr>
							<td>Начальные показания</td>
							<td>
								<input name="Start_readings" type="text" value='' size="100" maxlength="150" style="width:100%">
							</td>
						</tr>
                        <tr>
							<td>Модель</td>
							<td>
								<input name="Model" type="number" value='' size="100" maxlength="150" style="width:100%">
							</td>
						</tr>
                        <tr>
							<td>Производитель</td>
							<td>
								<input name="Manufacturer" type="text" value='' size="100" maxlength="150" style="width:100%">
							</td>
						</tr>
                        <tr>
							<td>Дата следующей проверки</td>
							<td>
								<input name="Next_check_date" type="text" value='' size="100" maxlength="150" style="width:100%">
							</td>
						</tr>
                        <tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">
								<input name="btnUpdateOK" type="submit" value="OK" />
								&nbsp;
								<button type="button" id="btnUpdateCancel">Отмена</button>
							</td>
						</tr>
            </table>
        </form>
        </dialog>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Номер</th>
                            <th scope="col">Дата выпуска</th>
                            <th scope="col">Начальное значение</th>
                            <th scope="col">Модель</th>
                            <th scope="col">Производитель</th>
                            <th scope="col">Дата следующей проверки</th>
                            <th scope="col"><img class = "IconAction" src = "../Images/Insert.png" onclick = "Update(undefined)"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
            $sql = mysql_query( 'SELECT * FROM devices' );
            while( $result = mysql_fetch_array( $sql ) ) {
                        echo'<tr>';
                            echo'<td>'.$result[number].'</td>';
                            echo'<td>'.$result[release_date].'</td>';
                            echo'<td>'.$result[start_readings].'</td>';
                            echo'<td>'.$result[model].'</td>';
                            echo'<td>'.$result[manufacturer].'</td>';
                            echo'<td>'.$result[next_check_date].'</td>';
                            echo'<td><img class = "IconAction" src = "Images/Edit.png" onclick = "Update(
                            ' . $result[id] . ', 
                            \'' . $result[number] . '\', 
                            \'' . $result[release_date] . '\', 
                            \'' . $result[start_readings] . '\', 
                            \'' . $result[model] . '\', 
                            \'' . $result[manufacturer] . '\', 
                            \'' . $result[next_check_date] . '\')"></td>';
                            echo '	<td><img class = "IconAction" src = "../Images/Delete.png" onclick = "Delete(\'form-devices\', ' . $result['id'] . ')"></td>';
                        echo'</tr>';
                        }
        ?>
        
                    </tbody>
                </table>
                
                
    </div>
    
    <div class="container" id="block-footer">
        <?php
	       include ("block-footer.php");
        ?>
    </div>
</div>

<?php
			mysql_close();
		?>
		
		<script language="JavaScript" src="_DialogUpdate.js"></script>
		<script language="JavaScript" src="_DialogDelete.js"></script>
</body>
</html>