<div class="wrapper_left">
    <table id="data_table">
    	<?php
    	    $where = "WHERE Objects.CompanyId = '".$company_admin['CompanyId']."'";
			
			if ( isset( $_GET[ 'ParentId' ] ) ) {
				$where = ( empty( $where ) ? "WHERE " : " AND " ) . "( Objects.ParentId = " . $_GET[ 'ParentId' ] . " )";
			}
    	
             $ct=1;
    		// вывод данных таблицы
    		$query1 ="SELECT
    		            Objects.ObjectId AS ObjectId,
    		            IFNULL(Objects.Name, '') AS Name,
                        Objects.KadastrNo AS KadastrNo, 
                        Objects.ParentId AS ParentId,
                        IFNULL(Parents.Name,'') AS ParentName,
                        IFNULL(Parents.KadastrNo,'') AS ParentKadastrNo, 
                        Objects.ObjectType AS ObjectType,
                        Objects.Square AS Square, 
                        Objects.Address AS Address, 
                        Objects.Comment AS Comment,
                        IFNULL(ManagementCompany.Name, '') AS ManagName, 
                        Objects.ObjectId AS ObjectId 
                      FROM Objects AS Objects
                      LEFT JOIN Objects AS Parents ON (Parents.ObjectId = Objects.ParentId)
                      LEFT JOIN ManagementCompany AS ManagementCompany ON(Objects.CompanyId = ManagementCompany.CompanyId)
                      " . $where . "
                      ORDER BY IFNULL(Parents.KadastrNo,''), 
                               IFNULL(Parents.Name,''),
                               KadastrNo,
                               Name";
    		$result1 = mysqli_query($link, $query1) or die("Ошибка " . mysqli_error($link)); 
    
    		$fn = array("ID","Краткое наименование","Кадастровый номер", "Родитель", "Тип объекта","Площадь объекта недвижимости","Адрес объекта недвижимости","Комментарий","Управляющая компания");

    		$ct = 1;
    	?>
    	<tbody>
    		<tr id='tr_parent_0'>
                <?php
                    foreach($fn as $nm)
                        {
                            echo "<th>".$nm."</th>";
                        }
                ?>
    		</tr>
    	<?php
    		if($result1)
    		{
    		    while($row1 = mysqli_fetch_array($result1)){
    		    	echo "<tr id='tr_parent_".$ct."'>";
    				$ct = $ct + 1;
    				echo "<td>".($row1['ObjectId'])."</td>";
    				echo "<td>".($row1['Name'])."</td>";
    				echo "<td>".($row1['KadastrNo'])."</td>";

                    // Вывод кадастровых номеров по id родителя
                    echo "<td>" . $row1[ 'ParentName' ] . ( empty( $row1[ 'ParentKadastrNo' ] ) ? "" : " (" . $row1[ 'ParentKadastrNo' ] . ")" ) . "</td>";

                    switch ($row1['ObjectType']) {
                        case '1':
                            echo "<td>Территория</td>";
                            break;
                        case '2':
                            echo "<td>Участок</td>";
                            break;
                        case '3':
                            echo "<td>Здание</td>";
                            break;
                        case '4':
                            echo "<td>Помещение и т.д.</td>";
                            break;
                    }

                    echo "<td>".($row1['Square'])."</td>";
                    echo "<td>".($row1['Address'])."</td>";
                    echo "<td>".($row1['Comment'])."</td>";
                    echo "<td>".($row1['ManagName'])."</td>";
    				echo"</tr>";
    				
    				$data[] = $row1['ObjectId'];
    			}
    		}
    	?>
    	</tbody>
    </table>
</div>
<div class="table_buttons" style="width: 10%">
    <table>
        <tr id='tr_child_0'>
            <td id='add_elem' width='20px'><a href='./create_update_forms/form_objects.php?ObjectId=-1'><img src='../images/Insert.png' alt='Добавить запись'></a></td>
    	</tr>		
		<?php
			if ( isset( $data ) ) {
				$ct = 1;

				foreach ( $data as $value ) {
					echo "<tr id='tr_child_" .$ct. "'>";
					echo "<td width='20px'><a href='./create_update_forms/form_objects.php?ObjectId=" . $value . "'><img src='../images/Edit.png' alt='Изменить запись'></a></td>";
					echo "</tr>";

					$ct = $ct + 1;
				}
			}

			require_once '../includes/modal.php';
		?>
	</table>
</div>
