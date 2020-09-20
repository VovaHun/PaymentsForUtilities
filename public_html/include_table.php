<div class = "title_wrapper">
	<div class = "content_title">
		<h5>Показания приборов учёта</h5>
	</div>
</div>

<div class = "">
	<?php
		
		if($PersonalId == NULL) 
		{
			$PersonalId = -1;
		}
		else
		{
			$PersonalId = $_GET['persid'];
		}
		
        $query1  = "SELECT AccountDevices.*, Services.Name, TariffTypes.Name, DeviceModels.Name, Devices.Name, DeviceIndications.Indications
            	  FROM AccountDevices
            	  LEFT JOIN Services ON (Services.ServiceId = AccountDevices.ServiceId)
            	  LEFT JOIN TariffTypes ON (TariffTypes.TariffId = AccountDevices.TariffId)
            	  LEFT JOIN  Devices ON (Devices.DeviceId = AccountDevices.DeviceId)
            	  LEFT JOIN  DeviceModels ON (DeviceModels.ModelId = Devices.ModelId)
            	  LEFT JOIN ( SELECT 
                                 DeviceIndications.DeviceId AS DeviceId,
                                 IFNULL( DeviceIndications.Indications, 0 ) AS Indications
                             FROM 
                                 DeviceIndications AS DeviceIndications
                             RIGHT JOIN ( SELECT 
                                              DeviceIndications.DeviceId AS DeviceId,
                                              MAX( DeviceIndications.Date ) AS Date
                                          FROM 
                                              DeviceIndications AS DeviceIndications
                                          WHERE 
                                              ( DeviceIndications.Fixed = 1 )
                                          GROUP BY
                                              DeviceIndications.DeviceId ) AS DeviceIndicationsMax ON 
                                  ( DeviceIndicationsMax.DeviceId = DeviceIndications.DeviceId ) AND
                                  ( DeviceIndicationsMax.Date = DeviceIndications.Date )
                             WHERE 
                                 ( DeviceIndications.Fixed = 1 ) ) AS DeviceIndications ON (DeviceIndications.DeviceId = Devices.DeviceId)
            	  WHERE AccountDevices.AccountId = ".$PersonalId." ";

	    $result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
	    
		$num_columns = $result1->field_count;

		$fn = $names_ar['AccountDevices'];

		$okey = array();
        
        $end = ( is_null( @$_GET['end'] ) ? '0' :  @$_GET['end'] );
        $div = ( is_null( @$_GET['div'] ) ? '0' :  @$_GET['div'] );
       
		$query2 = "SELECT DeviceId, Date FROM DeviceIndications";

		$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link)); 
		$row2 = mysqli_fetch_array($result2);
	
		if(isset($result1))
		{
		    while($row1 = mysqli_fetch_array($result1))
		    {
		    	echo "<ul type=none><div id = '_div_name'>".$row1[7]."</div>";
		    	echo "<li>";
		  		echo "<table id=_data_table>";
		  		echo "<form class = '' method = 'post' action = '/insert_cod.php'>";
		  		echo  "<tbody>";
		  		echo  "<tr>";
		  		echo  "<td id=_st_td_1>Прибор учета</td>";
		    	echo  "<td id=_st_td_1>Предыдущие показания</td>";
		    	echo  "<td id=_st_td_1>Показания прибора учета</td>";
		    	echo  "</tr>";
		  		echo  "<tr>";
		  		echo  "<td id=_st_td_2>".$row1[8]." ".$row1[9]."</td>";
		    	echo  "<td id=_st_td_2>".$row1[10]."</td>";

		    	echo  "<td id=_st_td_2><input type=text id = 'text1' name=Indications pattern = '\d+(\.\d{2})?' placeholder = '0.00' value = '' required></td>";

		    	echo  "<input type=hidden name=DeviceId value = '$row1[3]'>";
		    	echo  "<input type=hidden name=Date value = '$row1[4]'>";
		    	echo  "<input type=hidden name=personalId value = '$PersonalId'>";
		    	echo  "<td id=_st_td_3><input id = 'btn' type=submit  class=confirm value = 'Сохранить'></td>";
		    	echo  "</tr>";
		    	
		    	echo  "</tbody>";
		    	echo "</form>";
		    	echo "</table>";
		    	echo "</li>";
		    	echo "</ul>";
		    	
		    	if($end == 1 && $row1[3] == $div){
		    		echo "<div>Данные устройства ".$row1[9]." успешно добавленны</div>";
		    	}
		    	else
		    	{
		    		echo "";
		    	}
		    	echo "<hr>";
	    	}
		}
	?>
</div>

<style type = "text/css">
 #_st_td_1 {
  text-align: left;
  padding: 5px;
  width: 50%;
  border: 1px solid black;
  padding-left:10px ;
  font: 12pt   georgia ;
}
 #_st_td_2 {
  text-align: left;
  padding: 5px;
  width: 40%;
  border: 1px solid black;
  padding-left:10px ;
  font: 12pt sans-serif;
}
 #_st_td_3 {
  text-align: left;
  padding: 5px;
  width: 90%;
  border: 1px solid black;
  padding-left:10px ;
}
tr:nth-child(even) {background-color: #f2f2f2;}
#_div_name{
	font: 15pt sans-serif;
	padding-bottom: 10px;
}
#_data_table{
	width: 95%;
	border: 1px solid black;
}

#btn{
	background-color: #addfad;
}
</style>


